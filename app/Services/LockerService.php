<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LockerService
{
    public function __construct()
    {}

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
