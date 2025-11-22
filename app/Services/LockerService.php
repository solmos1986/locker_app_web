<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class LockerService
{
    public function __construct()
    {}

    public function getLockerStatus($locker_id)
    {
        Log::info("LockerService getLockerStatus " . jsonLog($locker_id));
        $movements = DB::table('movement')
            ->select('movement.*')
            ->join('door', 'door.door_id', 'movement.door_id')
            ->join('controller', 'controller.controller_id', 'door.controller_id')
            ->where('controller.locker_id', $locker_id)
            ->where('movement.building_id', 1) //getUser()->get('client_id')
            ->get();

        $depataments = DB::table('department')
            ->select('department.*')
            ->where('department.building_id', 1)
            ->get();

        $locker = DB::table('locker')
            ->select(
                'locker.locker_id',
                'locker.name',
                'locker.address',
                'locker.state',
            )
            ->where('locker.locker_id', $locker_id)
            ->first();

        $building = DB::table('building')
            ->select(
                'building.building_id',
                'building.company_id',
                'building.name',
                'building.phone',
                'building.manager',
                'building.address',
                'building.code'
            )
            ->join('locker', 'locker.building_id', 'building.building_id')
            ->where('locker.locker_id', $locker_id)
            ->first();

        $locker->doors = $this->getDoors($locker_id);
        return [
            'locker'      => $locker,
            'depataments' => $depataments,
            'movements'   => $movements,
            'building'    => $building,
        ];
    }

    private function getDoors($locker_id)
    {
        Log::info("LockerService getDoors " . jsonLog($locker_id));
        $doors = DB::table('controller')
            ->select(
                'door.door_id',
                'door.name',
                'door.state',
                'door.order',
                'door_size.door_size_id',
                'door_size.name as name_size',
            )
            ->join('door', 'door.controller_id', 'controller.controller_id')
            ->join('door_size', 'door_size.door_size_id', 'door.door_size_id')
            ->where('controller.locker_id', $locker_id)
        //->where('locker.client_id', Auth::user()->getClient->client_id)
            ->get();
        Log::info("LockerService getDoors => " . jsonLog($doors));
        foreach ($doors as $key => $door) {
            $ultimo_movimiento = DB::table('movement')
                ->select(
                    'movement.movement_id',
                    'movement.id_ref',
                    'movement.door_id',
                    DB::raw('DATE_FORMAT(movement.update_at, "%Y/%m/%s %H:%i:%s") as update_at'),
                    'type_movement.name'
                )
                ->join('type_movement', 'type_movement.type_movement_id', 'movement.type_movement_id')
                ->where('movement.door_id', $door->door_id)
                ->orderBy('movement.movement_id', 'DESC')

                ->first();
            if ($ultimo_movimiento) {
                $door->tipo_movimiento = $ultimo_movimiento->name;
                $door->update_at       = $ultimo_movimiento->update_at;
            } else {
                $door->tipo_movimiento = '';
                $door->update_at       = '';
            }
        }
        return $doors;
    }

    public function getRequirement()
    {
        Log::info("LockerService getRequirement " . jsonLog([]));
        /* $size_door  = DB::table('door_size')->get();
        $controller = DB::table('controller')->where('controller.',getUser()->get('client_id'))->get(); */
        $type_lockers = DB::table('type_locker')->get();
        //type_locker
        return [
            'type_lockers' => $type_lockers,
        ];
    }

    public function detailed_movement($id_ref)
    {
        Log::info("LockerService detailed_movement " . jsonLog($id_ref));
        $detailed_activity = new stdClass();
        $entrada           = DB::table('movement')
            ->select(
                'department.name as departament',
                'door.name as door',
                'movement.id_ref',
                'movement.status_integrate',
                'movement.status_notificate',
                'type_movement.name as type_movement',
                'movement.create_at'
            )
            ->where('movement.id_ref', $id_ref)
            ->where('movement.type_movement_id', 1)
            ->join('type_movement', 'type_movement.type_movement_id', 'movement.type_movement_id')
            ->join('department', 'department.department_id', 'movement.department_id')
            ->join('door', 'door.door_id', 'movement.door_id')
            ->first();
        $salida = DB::table('movement')
            ->select(
                'department.name as departament',
                'door.name as door',
                'movement.id_ref',
                'movement.status_integrate',
                'movement.status_notificate',
                'type_movement.name as type_movement',
                'movement.create_at'
            )
              ->where('movement.id_ref', $id_ref)
            ->where('movement.type_movement_id', 2)
            ->join('type_movement', 'type_movement.type_movement_id', 'movement.type_movement_id')
            ->join('department', 'department.department_id', 'movement.department_id')
            ->join('door', 'door.door_id', 'movement.door_id')
            ->first();
        $detailed_activity->entrada = $entrada;
        $detailed_activity->salida  = $salida;
        return $detailed_activity;
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

    public function storeLocker(
        $locker_id,
        $name,
        $address,
        $type_locker_id
    ) {
        Log::info("LockerService storeLocker " . jsonLog([$name, $address, $type_locker_id]));
        $insert = DB::table('locker')->insertGetId([
            'client_id'      => getUser()->get('client_id'),
            'type_locker_id' => $type_locker_id,
            'name'           => $name,
            'address'        => $address,
        ]);
        return $insert;
    }

    public function editLocker($locker_id)
    {
        Log::info("LockerService editLocker " . jsonLog($locker_id));
        $locker = DB::table('locker')
            ->select(
                'locker.locker_id',
                'locker.name',
                'locker.address',
                'locker.type_locker_id',
                'locker.state',
            )
            ->where(
                "locker_id", $locker_id)
            ->first();
        return $locker;
    }

    public function updateLocker($locker_id, $name, $address, $type_locker_id)
    {
        Log::info("LockerService updateLocker " . jsonLog([$name, $address, $type_locker_id]));
        $update = DB::table('locker')
            ->where('locker.locker_id', $locker_id)->update([
            'type_locker_id' => $type_locker_id,
            'name'           => $name,
            'address'        => $address,
        ]);
        $update = $this->editLocker($locker_id);
        return $update;
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
