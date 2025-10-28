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
        Log::info("MovementService storeMovement " . jsonLog([$department_id, $door_id, $code, $id_ref]));
        Log::info("MovementService storeMovement getLocker " . jsonLog(getLocker()));

        $id          = Str::uuid();
        $movement_id = DB::table('movement')->insertGetId([
            "department_id"    => $department_id,
            "door_id"          => $door_id,
            "id_ref"           => $id_ref,
            "building_id"      => getLocker()->get('building_id'),
            "code"             => $code,
            "type_movement_id" => 1,
        ]);

        $movement = DB::table('movement')
            ->join('department', 'department.department_id', 'movement.department_id')
            ->where('movement.movement_id', $movement_id)
            ->first();

        $data = [
            "id"              => $id,
            "Idcondominio"    => env("ID_CONDOMINIO_EXPERIENCE"),
            "size"            => "medium",
            "deliveryToken"   => $code,
            "externalOrderId" => "delivery",
            "publicLockerId"  => env("ID_CONDOMINIO_EXPERIENCE"),
            "collectToken"    => $code,
            "status"          => "allocated",
            "senderId"        => "delivery",
            "receiverId"      => $movement->name,
            "activate"        => true,
            "collected"       => false,
            "delivered"       => true,
        ];
        Log::info("MovementService storeMovement set enviar server  " . jsonLog(env("URL_APP_EXPERIENCE") . "/api/lockers-v1"));
        Log::info("MovementService storeMovement set data  " . jsonLog($data));
        Log::info("MovementService storeMovement set token  " . jsonLog(env("TOKEN_EXPERIENCE")));
        $client = new \GuzzleHttp\Client();

        $this->sendNotificationWhatsapp($code);
        /*  try {
            $response = $client->post(env("URL_APP_EXPERIENCE") . "/api/lockers-v1", [
                'headers' => [
                    'Authorization' => 'Bearer ' . env("TOKEN_EXPERIENCE"),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'body'    => json_encode($data),
            ]);
            Log::info("MovementService storeMovement status " . jsonLog($response->getStatusCode()));
            Log::info("MovementService storeMovement response " . jsonLog(json_decode($response->getBody()->getContents(), true)));
            $update = DB::table('movement')
                ->where('movement_id', $movement_id)->update([
                "send_delivery" => 1,
            ]);
            Log::info("MovementService movimiento modificado " . jsonLog($update));
        } catch (\Throwable $th) {
            Log::error($th);
            Log::info("MovementService storeMovement error al notificar al servidor");
        } */

    }

    public function updateMovement($movement_id)
    {
        Log::info("MovementService updateMovement($movement_id)");
        $update = DB::table('movement')
            ->where('movement_id', $movement_id)
            ->update([
                "delivered" => 1,
            ]);
        $movement = DB::table('movement')->join('user', 'movement.user_id', 'user.user_id')->where('movement.movement_id', $movement_id)
            ->first();
        $data = [
            "id"              => $movement->id_ref,
            "Idcondominio"    => env("ID_CONDOMINIO_EXPERIENCE"),
            "size"            => "medium",
            "deliveryToken"   => $movement->code,
            "externalOrderId" => "delivery",
            "publicLockerId"  => env("ID_CONDOMINIO_EXPERIENCE"),
            "collectToken"    => $movement->code,
            "status"          => "collected",
            "senderId"        => "delivery",
            "receiverId"      => $movement->name,
            "activate"        => true,
            "collected"       => true,
            "delivered"       => true,
        ];
        Log::info("MovementService updateMovement set enviar server  " . jsonLog(env("URL_APP_EXPERIENCE") . "/api/lockers-v1"));
        Log::info("MovementService updateMovement set data  " . jsonLog($data));
        Log::info("MovementService updateMovement set token  " . jsonLog(env("TOKEN_EXPERIENCE")));
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->post(env("URL_APP_EXPERIENCE") . "/api/lockers-v1", [
                'headers' => [
                    'Authorization' => 'Bearer ' . env("TOKEN_EXPERIENCE"),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'body'    => json_encode($data),
            ]);
            Log::info("MovementService updateMovement status " . jsonLog($response->getStatusCode()));
            Log::info("MovementService updateMovement response " . jsonLog(json_decode($response->getBody()->getContents(), true)));
            $update = DB::table('movement')
                ->where('movement_id', $movement_id)->update([
                "send_completed" => 1,
            ]);
            Log::info("MovementService movimiento modificado " . jsonLog($update));
        } catch (\Throwable $th) {
            Log::error($th);
            Log::info("MovementService updateMovement error al notificar al servidor");
        }
    }

    function sendNotificationWhatsapp($code)
    {
        Log::info("MovementService sendNotificationWhatsapp " . jsonLog([$code]));
        try {
            $data = [];

            $client = new \GuzzleHttp\Client();

            Log::info("MovementService sendNotificationWhatsapp url " . jsonLog("https://smart-lock.aplus-security.com/movement/$code"));
            $response = $client->request("GET","https://smart-lock.aplus-security.com/movement/$code", [
                'headers' => [
                    //'Authorization' => 'Bearer ' . env("TOKEN_EXPERIENCE"),
                    'Content-Type' => 'application/json',
                    'Accept'       => '*/*',
                ],
                //'body'    => json_encode($data),
                'verify' => true,
            ]);
            Log::info("MovementService sendNotificationWhatsapp " . jsonLog($response->getStatusCode()));
            $body = $response->getBody();
            Log::info("MovementService sendNotificationWhatsapp " . jsonLog($body->getContents()));
        } catch (\Throwable $th) {
            Log::error($th);
            Log::info("MovementService sendNotificationWhatsapp error al notificar al servidor");
        }
    }
}
