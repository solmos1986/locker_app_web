<?php
namespace App\Services;

use function Laravel\Prompts\select;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BuildingService
{
    public function __construct()
    {}

    public function bluildingStatus($building_id)
    {
        Log::info("DashboardService info " . jsonLog([$building_id]));
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

        $building = DB::table('building')
            ->select(
                'building_id',
                'company_id',
                'name',
                'phone',
                'manager',
                'address',
                'code',
            )
            ->where('building_id', $building_id)
            ->first();

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
            'lockers'  => $lockers,
            'users'    => $user,
            'building' => $building,
        ];
    }

    public function listBuild()
    {
        Log::info("BuildingService listBuild " . jsonLog([]));
        $buildings = DB::table('building')
            ->select(
                'building.building_id',
                'building.company_id',
                'building.name',
                'building.code',
                'building.address'
            )
            ->join('company', 'company.company_id', 'building.company_id')
            ->where('company.company_id', 1)
            ->get();
        $users = DB::table('users')
            ->select(
                'id',
                'name',
                'email',
                'celular',
            )
            ->get();
        return [
            'buildings' => $buildings,
            'users'     => $users,
        ];
    }

    public function store(
        $building_id,
        $company_id,
        $name,
        $code,
        $address,
        $manager,
        $phone
    ) {
        Log::info("BuildingService store " . jsonLog([
            $building_id,
            $company_id,
            $name,
            $code,
            $address,
            $manager,
            $phone,
        ]));
        $building = DB::table('building')
            ->insertGetId(
                [
                    'building_id' => $building_id,
                    'company_id'  => $company_id,
                    'name'        => $name,
                    'code'        => $code,
                    'address'     => $address,
                    'manager'     => $manager,
                    'phone'       => $phone,
                ]
            );
        return $building;
    }

    public function edit($building_id)
    {
        Log::info("BuildingService edit " . jsonLog([$building_id]));
        $building = DB::table('building')
            ->select(
                'building.building_id',
                'building.company_id',
                'building.name',
                'building.code',
                'building.manager',
                'building.phone',
                'building.address'
            )
            ->where('building.building_id', $building_id)
            ->first();
        return $building;
    }

    public function update(
        $building_id,
        $company_id,
        $name,
        $code,
        $address,
        $manager,
        $phone
    ) {
        Log::info("BuildingService update " . jsonLog([
            $building_id,
            $company_id,
            $name,
            $code,
            $address,
            $manager,
            $phone,
        ]));
        $building = DB::table('building')
            ->where('building.building_id', $building_id)
            ->update(
                [
                    'company_id' => $company_id,
                    'name'       => $name,
                    'code'       => $code,
                    'address'    => $address,
                    'manager'    => $manager,
                    'phone'      => $phone,
                ]
            );
        return $building;
    }

    public function delete($building_id)
    {
        $building = DB::table('building')
            ->where('building.building_id', $building_id)
            ->delete();
        return $building;
    }
}
