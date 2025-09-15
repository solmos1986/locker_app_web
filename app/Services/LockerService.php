<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LockerService
{
    public function __construct()
    {}

    public function getLockerStatus($locker_id)
    {
        Log::info("LockerService getLockerStatus " . jsonLog($locker_id));
        $locker = DB::table('locker')
            ->select(
                'locker.locker_id',
                'locker.macAdd',
                'locker.state',
            )
            ->where('locker.locker_id', $locker_id)
        //->where('locker.client_id', Auth::user()->getClient->client_id)
            ->first();
        $locker->doors = $this->getDoors($locker_id);
        return $locker;
    }

    private function getDoors($locker_id)
    {
        Log::info("LockerService getDoors " . jsonLog($locker_id));
        $doors = DB::table('controller')
            ->select(
                'door.door_id',
                'door.number',
                'door.state',
                'door.orden',
                'door.channel',
                'door_size.door_size_id',
                'door_size.name as name_size',
            )
            ->join('door', 'door.controller_id', 'controller.controller_id')
            ->join('door_size', 'door_size.door_size_id', 'door.door_size_id')
            ->where('controller.locker_id', $locker_id)
        //->where('locker.client_id', Auth::user()->getClient->client_id)
            ->get();
        Log::info("LockerService getDoors => " . jsonLog($doors));
        return $doors;
    }

    public function getLockers()
    {
        Log::info("LockerService getLockers " . jsonLog([]));
        $users = DB::table('locker')
            ->select(
                'locker.locker_id',
                'locker.macAdd',
                'locker.state',
            )
            ->where('locker.client_id', Auth::user()->getClient->client_id)
            ->get();
        return $users;
    }

    public function storeLocker($macAdd)
    {
        Log::info("LockerService storeLocker " . jsonLog($macAdd));
        $insert = DB::table('locker')->insert([
            "macAdd"    => $macAdd,
            "client_id" => Auth::user()->getClient->client_id,
        ]);
        return $insert;
    }

    public function editLocker($locker_id)
    {
        Log::info("LockerService editLocker " . jsonLog($locker_id));
        $user = DB::table('locker')->where(
            "locker_id", $locker_id);
        return $user;
    }

    public function updateLocker($macAdd)
    {
        Log::info("LockerService updateUser " . jsonLog($macAdd));
        $insert = DB::table('locker')->insert([
            "macAdd"    => $macAdd,
            "client_id" => Auth::user()->getClient->client_id,
        ]);
        return $insert;
    }

    public function deleteLocker($locker_id)
    {
        Log::info("LockerService deleteLocker " . jsonLog($locker_id));
        $delete = DB::table('locker')->where(
            "locker_id", $locker_id)
            ->delete();
        return $delete;
    }
}
