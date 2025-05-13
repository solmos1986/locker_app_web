<?php

namespace App\Services\AppMovil;

use Exception;
use Illuminate\Support\Facades\DB;
use stdClass;

class DatabaseService
{
    public function __construct() {}

    public function GetDataBase()
    {

        $users = DB::table('users')->get();
        $lockers = DB::table('lockers')->get();
        $database = new stdClass();
        $database->users = $users;
        $database->lockers = $lockers;
        return $database;
    }
}
