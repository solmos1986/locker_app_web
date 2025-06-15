<?php

namespace App\Services\AppMovil;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class DatabaseService
{
    public function __construct() {}

    public function GetDataBase()
    {
        Log::info("DatabaseService GetDataBase");
        $clients = DB::table('client')->get();
        $lockers = DB::table('locker')->get();
        $users = DB::table('user')->get();
        $controllers = DB::table('controller')->get();
        $door_sizes = DB::table('door_size')->get();
        $doors = DB::table('door')->get();
        $movements = DB::table('movement')->get();
        $request_comand = DB::table('request_comand')->get();
        $response_comand = DB::table('response_comand')->get();

        $database = new stdClass();
        $database->clients = $clients;
        $database->lockers = $lockers;
        $database->users = $users;
        $database->controllers = $controllers;
        $database->door_sizes = $door_sizes;
        $database->doors = $doors;
        $database->movements = $movements;
        $database->request_comands = $request_comand;
        $database->response_comands = $response_comand;
        return $database;
    }
}
