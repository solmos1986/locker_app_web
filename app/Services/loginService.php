<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class LoginService 
{
    public function __construct() {}

    public function getCLientDefault($users_id)
    {
        Log::info("LoginService getCLientDefault " . jsonLog($users_id));
        $empresa = DB::table('user_client')
            ->where('user_client.user_id', $users_id)
            ->first();
        return  $empresa;
    }

    public function getChangeCompany($users_id, $client_id)
    {
        Log::info("LoginService getChangeCompany " . jsonLog($users_id));
        $empresa = DB::table('user_client')
            ->where('user_client.user_id', $users_id)
            ->where('user_client.client_id', $client_id)
            ->first();
        return  $empresa;
    }
}
