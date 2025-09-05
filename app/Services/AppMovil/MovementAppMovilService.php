<?php
namespace App\Services\AppMovil;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MovementAppMovilService
{
    public function __construct()
    {}
    public function storeMovement($user_id, $door_id, $code)
    {
        Log::info("MovementService storeMovement " . jsonLog([$user_id, $door_id, $code, Auth::user()->getClient->client_id]));
    }
}
