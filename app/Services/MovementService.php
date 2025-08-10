<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class MovementService
{
    public function __construct() {}

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
                'movement.create_at',
                'movement.delivered',
            )
            ->join('door', 'door.door_id', 'movement.door_id')
            ->join('user', 'user.user_id', 'movement.user_id')
            ->where('movement.client_id', Auth::user()->getClient->client_id)
            ->get();
        return $movements;
    }

    public function storeMovement($user_id, $door_id, $code)
    {
        Log::info("MovementService storeMovement " . jsonLog([$user_id, $door_id, $code]));
        $insert = DB::table('movement')->insert([
            "user_id" => $user_id,
            "door_id" => $door_id,
            "code" => $code,
        ]);
    }

    public function updateMovement($movement_id)
    {
        Log::info("MovementService updateMovement($movement_id)");
        $update = DB::table('movement')
            ->where('movement_id', $movement_id)
            ->update([
                "delivered" => 1,
            ]);
    }
}
