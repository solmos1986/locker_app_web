<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class MovementService
{
    public function __construct()
    {}

    public function dataTable($locker_id, $pageSize, $pageIndex, $active, $direction, $search)
    {
        Log::info("MovementService dataTable " . jsonLog([$locker_id, $pageSize, $pageIndex, $active, $direction, $search]));
        $dataTable = new stdClass();
        $movements = DB::table('movement')
            ->select(
                'movement.movement_id',
                'department.name as department',
                'door.name as casillero',
                'movement.code',
                'movement.id_ref',
                'movement.status_notificate',
                'movement.create_at',
                DB::raw("GROUP_CONCAT( type_movement.name SEPARATOR ' / ') as state"),
                DB::raw("GROUP_CONCAT( IF(movement.status_integrate=1, 'si', 'no') SEPARATOR ' / ') as status_integrate"),
                // Notificación a Holding: "enviada" = la entrega (allocated) llegó a Holding;
                // "confirmada" = el retiro o la intervención (collected) llegó a Holding.
                DB::raw("MAX(IF(movement.type_movement_id = 1, movement.status_integrate, 0)) as holding_enviada"),
                DB::raw("MAX(IF(movement.type_movement_id IN (2, 4), movement.status_integrate, 0)) as holding_confirmada"),
                DB::raw("MAX(movement.type_movement_id IN (2, 4)) as cerrado"),
                // WhatsApp solo se envía al registrar la entrega.
                DB::raw("MAX(IF(movement.type_movement_id = 1, movement.status_notificate, 0)) as whatsapp_enviado"),
                // Fechas en UTC: la de la entrega y la del retiro (2) o intervención (4), si ya ocurrió.
                DB::raw("MIN(IF(movement.type_movement_id = 1, movement.create_at, NULL)) as fecha_entrega"),
                DB::raw("MAX(IF(movement.type_movement_id IN (2, 4), movement.create_at, NULL)) as fecha_cierre"),
                DB::raw("MAX(IF(movement.type_movement_id IN (2, 4), movement.type_movement_id, NULL)) as tipo_cierre"),
            )
            ->join('door', 'door.door_id', 'movement.door_id')
            ->join('controller', 'controller.controller_id', 'door.controller_id')
            ->join('type_movement', 'type_movement.type_movement_id', 'movement.type_movement_id')
            ->join('department', 'department.department_id', 'movement.department_id')
            ->where('controller.locker_id', $locker_id)
            ->groupBy(
                'movement.id_ref',
            )
            ->orderBy($active, $direction)
            ->skip($pageSize * ($pageIndex === 0 ? 1 : $pageIndex))
            ->paginate($pageSize, ['*'], null, ($pageIndex + 1));

        $locker = DB::table('locker')
            ->where('locker.locker_id', $locker_id)
            ->first();
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
        $dataTable->locker    = $locker;
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
