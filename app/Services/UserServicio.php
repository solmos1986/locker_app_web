<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserServicio
{
    public function __construct()
    {}

    public function getUser()
    {
        Log::info("UserServicio getUser " . jsonLog([]));
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

    public function storeUser($name)
    {
        Log::info("UserServicio storeUser " . jsonLog($name));
        $insert = DB::table('user')->insert([
            "name"      => $name,
            "client_id" => Auth::user()->getClient->client_id,
        ]);
        return $insert;
    }

    public function editUser($user_id)
    {
        Log::info("UserServicio editUser " . jsonLog($user_id));
        $user = DB::table('user')->where(
            "user_id", $user_id);
        return $user;
    }

    public function updateUser($name)
    {
        Log::info("UserServicio updateUser " . jsonLog($name));
        $insert = DB::table('user')->insert([
            "name"      => $name,
            "client_id" => Auth::user()->getClient->client_id,
        ]);
        return $insert;
    }

    public function deleteUser($user_id)
    {
        Log::info("UserServicio deleteUser " . jsonLog($user_id));
        $delete = DB::table('user')->where(
            "user_id", $user_id)
            ->delete();
        return $delete;
    }
}
