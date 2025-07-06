<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class MovementService
{
    public function __construct() {}

    public function storeMovement($user_id, $door_id, $code)
    {
        Log::info("MovementService storeMovement ". jsonLog([$user_id, $door_id, $code]));
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
