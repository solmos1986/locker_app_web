<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    public function __construct()
    {}

    public function info()
    {
        Log::info("DashboardService info " . jsonLog([]));
        $users = DB::table('user')
            ->where('user.client_id', getUser()->get('client_id'))
            ->get();
        $lockers = DB::table('locker')
            ->select(
                'locker.locker_id',
                'locker.address'
            )
            ->join('controller', 'controller.locker_id', 'locker.locker_id')
            ->where('locker.client_id', getUser()->get('client_id'))
            ->groupBy('locker.locker_id', 'locker.address')
            ->get();
        $movements = DB::table('movement')
            ->where('movement.client_id', getUser()->get('client_id'))
            ->get();
        return [
            'users'   => $users,
            'lockers' => $lockers,
            'movements' => $movements,
        ];
    }

}
