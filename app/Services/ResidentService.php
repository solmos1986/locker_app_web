<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResidentService
{
    public function __construct()
    {}

    public function getResidents()
    {
        Log::info("ResidentService/getusers " . jsonLog([]));
        $users = DB::table('user')
            ->select(
                'user.user_id',
                'user.name',
                'user.state',
            )
            ->where('user.client_id', Auth::user()->getClient->client_id)
            ->get();
        return $users;
    }

    public function storeResident($macAdd)
    {
        Log::info("ResidentService/storeResident " . jsonLog($macAdd));
        $insert = DB::table('user')->insert([
            "macAdd"    => $macAdd,
            "client_id" => Auth::user()->getClient->client_id,
        ]);
        return $insert;
    }

    public function editResident($user_id)
    {
        Log::info("ResidentService/editResident " . jsonLog($user_id));
        $resident = DB::table('user')->where(
            "user_id", $user_id)
            ->first();
        return $resident;
    }

    public function updateResident($macAdd)
    {
        Log::info("ResidentService/updateUser " . jsonLog($macAdd));
        $insert = DB::table('user')->insert([
            "macAdd"    => $macAdd,
            "client_id" => Auth::user()->getClient->client_id,
        ]);
        return $insert;
    }

    public function deleteResident($user_id)
    {
        Log::info("ResidentService/deleteResident " . jsonLog($user_id));
        $delete = DB::table('user')->where(
            "user_id", $user_id)
            ->delete();
        return $delete;
    }
}
