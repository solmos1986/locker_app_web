<?php
namespace App\Services\AppMovil;

use App\Models\Locker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;
use Tymon\JWTAuth\Facades\JWTAuth;

class DatabaseService
{
    public function __construct()
    {}

    public function GetDataBase()
    {
        Log::info("DatabaseService GetDataBase locker_id " . jsonLog(getLocker()));

        $department = DB::table('department')
            ->select('department.*')
            ->where('department.building_id', getLocker()->get('building_id'))
            ->get();

        $lockers = DB::table('locker')
            ->select('locker.*')
            ->where('locker.locker_id', getLocker()->get('locker_id'))
            ->get();

        $users = DB::table('user')
            ->select('user.*')
            ->join('department', 'user.department_id', '=', 'department.department_id')
            ->where('department.building_id', getLocker()->get('building_id'))
            ->get();

        $controllers = DB::table('controller')
            ->select('controller.*')
            ->where('controller.locker_id', getLocker()->get('locker_id'))
            ->get();

        $door_sizes = DB::table('door_size')
            ->select('door_size.*')
            ->get();

        $doors = DB::table('door')
            ->select('door.*')
            ->join('controller', 'controller.controller_id', '=', 'door.controller_id')
            ->where('controller.locker_id', getLocker()->get('locker_id'))
            ->get();

        $movements = DB::table('movement')
            ->select('movement.*')
            ->where('movement.building_id', getLocker()->get('building_id'))
            ->get();

        $request_comand = DB::table('request_comand')
            ->select('request_comand.*')
            ->join('door', 'door.door_id', '=', 'request_comand.door_id')
            ->join('controller', 'controller.controller_id', '=', 'door.controller_id')
            ->where('controller.locker_id', getLocker()->get('locker_id'))
            ->get();

        $response_comand = DB::table('response_comand')
            ->select('response_comand.*')
            ->join('request_comand', 'request_comand.request_comand_id', '=', 'response_comand.request_comand_id')
            ->join('door', 'door.door_id', '=', 'request_comand.door_id')
            ->join('controller', 'controller.controller_id', '=', 'door.controller_id')
            ->where('controller.locker_id', getLocker()->get('locker_id'))
            ->get();

        $database                   = new stdClass();
        $database->department       = $department;
        $database->lockers          = $lockers;
        $database->users            = $users;
        $database->controllers      = $controllers;
        $database->door_sizes       = $door_sizes;
        $database->doors            = $doors;
        $database->movements        = $movements;
        $database->request_comands  = $request_comand;
        $database->response_comands = $response_comand;
        return $database;
    }

    public function getLocker($locker_id)
    {
        $locker = Locker::where('locker_id', $locker_id)
            ->first();
        Log::info("DatabaseController locker " . jsonLog($locker));
        $customClaims = [
            "locker_id"      => $locker->locker_id,
            "building_id"    => $locker->building_id,
            "name"           => $locker->name,
            "address"        => $locker->address,
            "type_locker_id" => $locker->type_locker_id,
            "state"          => $locker->state,
        ];
        $token = JWTAuth::customClaims($customClaims)->fromUser($locker, $customClaims);
        return $token;
    }
}
