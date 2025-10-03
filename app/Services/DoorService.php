<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class DoorService
{
    public function __construct()
    {}

    public function dataTable($locker_id, $pageSize, $pageIndex, $active, $direction, $search)
    {
        Log::info("DoorService dataTable " . jsonLog([$pageSize, $pageIndex, $active, $direction, $search]));
        $dataTable = new stdClass();
        $doors     = DB::table('door')
            ->select(
                'door.*',
                'door_size.name as door_size_name',
                'controller.name as controller_name'
            )
            ->join('door_size', 'door_size.door_size_id', 'door.door_size_id')
            ->join('controller', 'door.controller_id', 'controller.controller_id')
            ->where('controller.locker_id', $locker_id)
            ->orderBy($active, $direction)
            ->paginate($pageSize);

        Log::info("MovementService dataTable movements " . jsonLog($doors->items()));
        $dataTable->paginate = [
            'length'    => $doors->count(),
            'pageIndex' => $pageIndex,
            'pageSize'  => $pageSize,
        ];
        $dataTable->sort = [
            'active'    => $active,
            'direction' => $direction,
        ];
        $dataTable->doors = $doors->items();
        return $dataTable;
    }
    public function requirement($locker_id)
    {
        Log::info("DoorService requirement " . jsonLog([$locker_id]));
        $door_sizes = DB::table('door_size')
            ->get();
        $controllers = DB::table('controller')
            ->where('controller.locker_id', $locker_id)
            ->get();
        return [
            'door_sizes'  => $door_sizes,
            'controllers' => $controllers,
        ];
    }
    public function storeDoor(
        $door_id,
        $door_size_id,
        $controller_id,
        $name,
        $state,
        $order
    ) {
        Log::info("DoorService storeDoor " . jsonLog([$door_id,
            $door_size_id,
            $controller_id,
            $name,
            $state,
            $order]));
        $door = DB::table('door')
            ->insertGetId([
                'door_size_id'  => $door_size_id,
                'controller_id' => $controller_id,
                'name'          => $name,
                'state'         => $state,
                'order'         => $order,
            ]);
        return $door;
    }
    public function editeDoor(
        $door_id
    ) {
        Log::info("DoorService editeDoor " . jsonLog([
            $door_id,
        ]));
        $door = DB::table('door')
        ->select(
            'door.door_id',
            'door.door_size_id',
            'door.controller_id',
            'door.name',
            'door.state',
            'door.order',
        )
            ->where('door_id', $door_id)
            ->first();
        return $door;
    }
    public function updateDoor(
        $door_id,
        $door_size_id,
        $controller_id,
        $name,
        $state,
        $order) {
        Log::info("DoorService editeDoor " . jsonLog([
            $door_id,
            $door_size_id,
            $controller_id,
            $name,
            $state,
            $order]));
        $door = DB::table('door')
            ->where('door_id', $door_id)
            ->update([
                'door_size_id'  => $door_size_id,
                'controller_id' => $controller_id,
                'name'          => $name,
                'state'         => $state,
                'order'         => $order,
            ]);
        $door = $this->editeDoor($door_id);
        return $door;
    }
    public function deleteDoor($locker_id)
    {
        Log::info("DoorService deleteDoor " . jsonLog([$locker_id]));
        $door_sizes = DB::table('door')
            ->where('door.door_id', $locker_id)
            ->delete();
        return;
    }
}
