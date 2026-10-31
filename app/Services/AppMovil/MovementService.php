<?php
namespace App\Services\AppMovil;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MovementService
{
    public function __construct()
    {

    }

    public function getMovimientos()
    {
        Log::info("MovementService getMovimientos " . jsonLog([]));
        Log::info("MovementService user " . jsonLog(Auth::user()->getCurrentRol));
        Log::info("MovementService VerificateRol " . jsonLog(VerificateRol('admin')));
        $movements = DB::table('movement')
            ->select(
                'movement.movement_id',
                'user.name as resident',
                'door.number as department',
                'movement.code',
                'movement.create_at',
                'movement.delivered',
            )
            ->join('door', 'door.door_id', 'movement.door_id')
            ->join('user', 'user.user_id', 'movement.user_id')
            ->where('movement.client_id', Auth::user()->getClient->client_id)
            ->get();
        return $movements;
    }

    public function storeMovement($department_id, $door_id, $code, $id_ref)
    {
        // Se respeta el id_ref de la tablet para que la BD local y el servidor compartan el mismo envío.
        $id_ref = empty($id_ref) ? Str::uuid()->toString() : $id_ref;
        Log::info("MovementService storeMovement " . jsonLog([$department_id, $door_id, $code, $id_ref]));
        Log::info("MovementService storeMovement getLocker " . jsonLog(getLocker()));

        $movement_id = DB::table('movement')->insertGetId([
            "department_id"    => $department_id,
            "door_id"          => $door_id,
            "id_ref"           => $id_ref,
            "building_id"      => getLocker()->get('building_id'),
            "code"             => $code,
            "type_movement_id" => 1,
        ]);
        // La puerta queda ocupada hasta el retiro; la tablet copia este estado al sincronizar.
        DB::table('door')->where('door_id', $door_id)->update(['state' => 0]);

        $verificate_movement = DB::table('movement')
            ->select(
                'movement.id_ref',
                'movement.department_id',
                'movement.type_movement_id',
                'movement.door_id',
                'movement.code',
                'movement.building_id',
                'department.name as nameDepartament',
                'door.name as numberDoor',
                'door_size.codigo as codigoSizeDoor'
            )
            ->join('department', 'department.department_id', 'movement.department_id')
            ->join('door', 'door.door_id', 'movement.door_id')
            ->join('door_size', 'door_size.door_size_id', 'door.door_size_id')
            ->where('movement.id_ref', $id_ref)
            ->where('movement.department_id', $department_id)
            ->where('movement.code', $code)
            ->where('movement.door_id', $door_id)
            ->where('movement.building_id', getLocker()->get('building_id'))
            ->where('movement.movement_id', $movement_id)
            ->first();
        Log::info("MovementService verificate_movement " . jsonLog([$verificate_movement]));

        $this->sendNotificationHolding(
            $verificate_movement->codigoSizeDoor,
            $verificate_movement->nameDepartament,
            $verificate_movement->door_id,
            $verificate_movement->code,
            $verificate_movement->id_ref,
            $movement_id
        );

        $this->sendNotificationWhatsapp($code, $movement_id);

    }

    public function updateMovement(
        $department_id,
        $door_id,
        $code,
        $id_ref
    ) {
        Log::info("MovementService updateMovement" . jsonLog([
            $department_id,
            $door_id,
            $code,
            $id_ref,
        ]));

        $building_id = getLocker()->get('building_id');

        // Busca la entrega (tipo 1) pendiente, es decir, sin un retiro (2) ni intervención (4) para el mismo id_ref.
        $pendingQuery = fn() => DB::table('movement')
            ->select(
                'movement.id_ref',
                'movement.department_id',
                'movement.type_movement_id',
                'movement.door_id',
                'movement.code',
                'movement.building_id',
                'department.name as nameDepartament',
                'door.name as numberDoor',
                'door_size.codigo as codigoSizeDoor'
            )
            ->join('department', 'department.department_id', 'movement.department_id')
            ->join('door', 'door.door_id', 'movement.door_id')
            ->join('door_size', 'door_size.door_size_id', 'door.door_size_id')
            ->where('movement.type_movement_id', 1)
            ->where('movement.department_id', $department_id)
            ->where('movement.code', $code)
            ->where('movement.door_id', $door_id)
            ->where('movement.building_id', $building_id)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('movement as retiro')
                    ->whereColumn('retiro.id_ref', 'movement.id_ref')
                    ->whereIn('retiro.type_movement_id', [2, 4]);
            })
            ->orderByDesc('movement.movement_id');

        // Primero por id_ref; si no coincide (entregas creadas antes de compartir el id_ref
        // con la tablet), por departamento + puerta + código.
        $verificate_movement = empty($id_ref)
            ? null
            : $pendingQuery()->where('movement.id_ref', $id_ref)->first();
        $verificate_movement = $verificate_movement ?? $pendingQuery()->first();
        Log::info("MovementService updateMovement verificate_movement " . jsonLog([$verificate_movement]));

        if ($verificate_movement) {
            $movement_id = DB::table('movement')->insertGetId([
                "department_id"    => $department_id,
                "door_id"          => $door_id,
                "id_ref"           => $verificate_movement->id_ref,
                "building_id"      => $building_id,
                "code"             => $code,
                "type_movement_id" => 2,
            ]);
            DB::table('door')->where('door_id', $door_id)->update(['state' => 1]);

            $this->modificateNotificationHolding(
                $verificate_movement->codigoSizeDoor,
                $verificate_movement->nameDepartament,
                $verificate_movement->door_id,
                $verificate_movement->code,
                $verificate_movement->id_ref,
                $movement_id
            );
        }
    }

    public function sendNotificationWhatsapp($code, $movement_id)
    {
        Log::info("MovementService sendNotificationWhatsapp " . jsonLog([$code, $movement_id]));
        try {
            $client = new \GuzzleHttp\Client([
                'verify' => false,
                'curl'   => [
                    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2, // Or other appropriate version
                ],
            ]);

            $url = env('URL_APP_WHATSAPP') . "/api/v1/movement/$code";
            Log::info("MovementService sendNotificationWhatsapp url " . jsonLog($url));
            $response = $client->request("GET", $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => '*/*',
                ],
                'verify'  => false,
                'timeout' => 60,
            ]);
            Log::info("MovementService sendNotificationWhatsapp " . jsonLog($response->getStatusCode()));
            $body = $response->getBody();
            Log::info("MovementService sendNotificationWhatsapp " . jsonLog($body->getContents()));
            // Guzzle lanza excepción ante 4xx/5xx: si llega aquí, la notificación se envió.
            $update = DB::table('movement')
                ->where('movement_id', $movement_id)
                ->update([
                    "status_notificate" => 1,
                ]);
            Log::info("MovementService sendNotificationWhatsapp modificado " . jsonLog($update));
        } catch (\Throwable $th) {
            Log::error($th);
            Log::info("MovementService sendNotificationWhatsapp error al notificar al servidor");
        }
    }

    public function sendNotificationHolding(
        $codigoSizeDoor,
        $nameDepartament,
        $door_id,
        $code,
        $id_ref,
        $movement_id
    ) {
        Log::info("MovementService sendNotificationHolding " . jsonLog([
            $codigoSizeDoor,
            $nameDepartament,
            $door_id,
            $code,
            $id_ref,
        ]));

        $building = DB::table('building')
            ->where('building.building_id', getLocker()->get('building_id'))
            ->first();

        try {
            $client = new \GuzzleHttp\Client();
            $data   = [
                "id"              => $id_ref,
                "Idcondominio"    => $building->code,
                "size"            => strtolower($codigoSizeDoor),
                "deliveryToken"   => $code,
                "externalOrderId" => "delivery",
                "publicLockerId"  => $building->code,
                "collectToken"    => $code,
                "status"          => "allocated",
                "senderId"        => "delivery",
                "receiverId"      => $nameDepartament,
                "activate"        => true,
                "collected"       => false,
                "delivered"       => true,
            ];
            $url = env("URL_APP_EXPERIENCE") . "/api/lockers-v1";
            Log::info("MovementService sendNotificationHolding url " . jsonLog($url));
            Log::info("MovementService sendNotificationHolding data " . jsonLog($data));
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env("TOKEN_EXPERIENCE"),
                    'Content-Type'  => 'application/json',
                    'Accept'        => '*/*',
                ],
                'body'    => json_encode($data),
            ]);
            Log::info("MovementService sendNotificationHolding status " . jsonLog($response->getStatusCode()));
            Log::info("MovementService sendNotificationHolding response " . jsonLog(json_decode($response->getBody()->getContents(), true)));
            $update = DB::table('movement')
                ->where('movement_id', $movement_id)
                ->update([
                    "status_integrate" => 1,
                ]);
            Log::info("MovementService sendNotificationHolding modificado " . jsonLog($update));
        } catch (\Throwable $th) {
            Log::error($th);
            Log::info("MovementService sendNotificationHolding error al notificar al servidor");
        }
    }

    public function modificateNotificationHolding(
        $codigoSizeDoor,
        $nameDepartament,
        $door_id,
        $code,
        $id_ref,
        $movement_id,
        $building_id = null
    ) {
        Log::info("MovementService modificateNotificationHolding " . jsonLog([
            $codigoSizeDoor,
            $nameDepartament,
            $door_id,
            $code,
            $id_ref,
            $building_id,
        ]));

        // Desde el panel (intervención) no hay token de locker: el edificio llega por parámetro.
        $building = DB::table('building')
            ->where('building_id', $building_id ?? getLocker()->get('building_id'))
            ->first();

        try {
            $client = new \GuzzleHttp\Client();
            $data   = [
                "id"              => $id_ref,
                "Idcondominio"    => $building->code,
                "size"            => strtolower($codigoSizeDoor),
                "deliveryToken"   => $code,
                "externalOrderId" => "delivery",
                "publicLockerId"  => $building->code,
                "collectToken"    => $code,
                "status"          => "collected",
                "senderId"        => "delivery",
                "receiverId"      => $nameDepartament,
                "activate"        => true,
                "collected"       => true,
                "delivered"       => true,
            ];
            $response = $client->post(env("URL_APP_EXPERIENCE") . "/api/lockers-v1", [
                'headers' => [
                    'Authorization' => 'Bearer ' . env("TOKEN_EXPERIENCE"),
                    'Content-Type'  => 'application/json',
                    'Accept'        => '*/*',
                ],
                'body'    => json_encode($data),
            ]);
            Log::info("MovementService modificateNotificationHolding status " . jsonLog($response->getStatusCode()));
            Log::info("MovementService modificateNotificationHolding response " . jsonLog(json_decode($response->getBody()->getContents(), true)));
            $update = DB::table('movement')
                ->where('movement_id', $movement_id)
                ->update([
                    "status_integrate" => 1,
                ]);
            Log::info("MovementService modificateNotificationHolding modificado " . jsonLog($update));
        } catch (\Throwable $th) {
            Log::error($th);
            Log::info("MovementService modificateNotificationHolding error al notificar al servidor");
        }
    }
}
