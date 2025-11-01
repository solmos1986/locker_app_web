<?php
namespace App\Services\AppMovil;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $this->sendNotificationHolding(
            $verificate_movement->codigoSizeDoor,
            $verificate_movement->nameDepartament,
            $verificate_movement->door_id,
            $verificate_movement->code,
            $verificate_movement->id_ref,
            $movement_id
        );

        //$this->sendNotificationWhatsapp($code);

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
            ->first();

        if ($verificate_movement) {
            $movement_id = DB::table('movement')->insertGetId([
                "department_id"    => $department_id,
                "door_id"          => $door_id,
                "id_ref"           => $id_ref,
                "building_id"      => getLocker()->get('building_id'),
                "code"             => $code,
                "type_movement_id" => 2,
            ]);

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

    function sendNotificationWhatsapp($code)
    {
        Log::info("MovementService sendNotificationWhatsapp " . jsonLog([$code]));
        try {
            $client = new \GuzzleHttp\Client([
                'verify' => false,
                'curl'   => [
                    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2, // Or other appropriate version
                ],
            ]);

            $url = "https://smart-lock.aplus-security.com/movement/$code";
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
        } catch (\Throwable $th) {
            Log::error($th);
            Log::info("MovementService sendNotificationWhatsapp error al notificar al servidor");
        }
    }

    function sendNotificationHolding(
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
        try {
            $client = new \GuzzleHttp\Client();
            $data   = [
                "id"              => $id_ref,
                "Idcondominio"    => env("ID_CONDOMINIO_EXPERIENCE"),
                "size"            => strtolower($codigoSizeDoor),
                "deliveryToken"   => $code,
                "externalOrderId" => "delivery",
                "publicLockerId"  => env("ID_CONDOMINIO_EXPERIENCE"),
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
            Log::info("MovementService sendNotificationHolding status " . jsonLog($data));
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

    function modificateNotificationHolding(
        $codigoSizeDoor,
        $nameDepartament,
        $door_id,
        $code,
        $id_ref,
        $movement_id
    ) {
        Log::info("MovementService modificateNotificationHolding " . jsonLog([
            $codigoSizeDoor,
            $nameDepartament,
            $door_id,
            $code,
            $id_ref,
        ]));
        try {
            $client = new \GuzzleHttp\Client();
            $data   = [
                "id"              => $id_ref,
                "Idcondominio"    => env("ID_CONDOMINIO_EXPERIENCE"),
                "size"            => strtolower($codigoSizeDoor),
                "deliveryToken"   => $code,
                "externalOrderId" => "delivery",
                "publicLockerId"  => env("ID_CONDOMINIO_EXPERIENCE"),
                "collectToken"    => $code,
                "status"          => "allocated",
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
