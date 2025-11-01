<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class MovementService
{
    public function __construct()
    {}

    public function dataTable($pageSize, $pageIndex, $active, $direction, $search)
    {
        Log::info("MovementService dataTable " . jsonLog([$pageSize, $pageIndex, $active, $direction, $search]));
        $dataTable = new stdClass();
        $movements = DB::table('movement')
            ->select(
                'movement.movement_id',
                'department.name as department',
                'door.name as casillero',
                'movement.code',
                'movement.id_ref',
                'movement.status_integrate',
                'movement.status_notificate',
                'type_movement.name as state',
                'movement.create_at'
            )
            ->join('door', 'door.door_id', 'movement.door_id')
            ->join('type_movement', 'type_movement.type_movement_id', 'movement.type_movement_id')
            ->join('department', 'department.department_id', 'movement.department_id')
            ->where('movement.building_id', 1) //getUser()->get('client_id')
            ->orderBy($active, $direction)
            ->paginate($pageSize)
            ->skip($pageSize*$pageIndex);

        Log::info("MovementService dataTable movements " . jsonLog($movements));
        $dataTable->paginate = [
            'length'    => $movements->total(),
            'pageIndex' => $pageIndex,
            'pageSize'  => $pageSize,
        ];
        $dataTable->sort = [
            'active'    => $active,
            'direction' => $direction,
        ];
        $dataTable->movements = $movements->items();
        return $dataTable;
    }

    public function storeMovement($user_id, $door_id, $code)
    {
        Log::info("MovementService storeMovement " . jsonLog([$user_id, $door_id, $code, getUser()->get('client_id')]));
        $insert = DB::table('movement')->insert([
            "user_id"   => $user_id,
            "door_id"   => $door_id,
            "client_id" => getUser()->get('client_id'),
            "code"      => $code,
        ]);
        Log::info("MovementService storeMovement insert " . jsonLog($insert));
    }

    public function updateMovement($movement_id)
    {
        Log::info("MovementService updateMovement($movement_id)");
        $update = DB::table('movement')
            ->where('movement_id', $movement_id)
            ->update([
                "delivered" => 1,
            ]);
    }
}
