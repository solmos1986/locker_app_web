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

     public function getCompany($users_id)
    {
        Log::info("LoginService getCompany " . jsonLog($users_id));
        $company = DB::table('user_company')
            ->select(
                'company.company_id',
                'company.name',
                'company.address',
                'company.owner'
            )
            ->join('company', 'company.company_id', 'user_company.company_id')
            ->where('user_company.users_id', $users_id)
            ->first();
        return $company;
    }

    public function getRolesBuilding($users_id)
    {
        Log::info("LoginService getRolesPermisos " . jsonLog($users_id));
        $rol_building = DB::table('users_rol')
            ->join('users_rol_building', 'users_rol_building.users_rol_id', 'users_rol.users_rol_id')
            ->where('users_rol.user_id', $users_id)
            ->get();
        return $rol_building;
    }
}
