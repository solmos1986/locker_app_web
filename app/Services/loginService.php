<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class LoginService
{
    public function __construct() {}

    public function login($email, $password)
    {
        Log::info("MovementService login " . jsonLog([$email, $password]));
       
    }

    public function updateMovement($movement_id)
    {
        Log::info("MovementService updateMovement($movement_id)");
      
    }
}
