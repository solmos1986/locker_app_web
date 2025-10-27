<?php
namespace App\Services;

use function Laravel\Prompts\select;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    public function __construct()
    {}

    public function info()
    {
        Log::info("DashboardService info " . jsonLog([]));
        Log::info("DashboardService getUser " . jsonLog(getUser()));

        $lockers = DB::table('locker')
            ->select(
                'locker.locker_id',
                'locker.name',
                'locker.address'
            )
            ->join('controller', 'controller.locker_id', 'locker.locker_id')
            ->where('locker.building_id', 1)
            ->groupBy('locker.locker_id', 'locker.address', 'locker.name')
            ->get();

        $user = DB::table('users')
            ->select(
                'id',
                'name',
                'email',
                'celular',
            )
            ->get();
        foreach ($lockers as $key => $locker) {
            $locker->doors = DB::table('controller')
                ->select(
                    'door.door_id',
                    'door.door_size_id',
                    'door.controller_id',
                    'door.name',
                    'door.order',
                    'door.state',
                )
                ->join('door', 'door.controller_id', 'controller.controller_id')
                ->where('controller.locker_id', $locker->locker_id)
                ->get();
        }
        return [
            'lockers' => $lockers,
            'users'   => $user,
        ];
    }

}
