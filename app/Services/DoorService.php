<?php
namespace App\Services;

use App\Services\AppMovil\MovementService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use stdClass;

class DoorService
{
    protected MovementService $movementService;

    public function __construct(MovementService $movementService)
    {
        $this->movementService = $movementService;
    }

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

        Log::info("DoorService dataTable movements " . jsonLog($doors->items()));
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

    public function openDoor($door_id)
    {
        Log::info("DoorService openDoor " . jsonLog([$door_id, getLocker()]));
        $door = DB::table('door')
            ->select('door.*', 'locker.locker_id', 'locker.building_id')
            ->join('controller', 'controller.controller_id', '=', 'door.controller_id')
            ->join('locker', 'locker.locker_id', '=', 'controller.locker_id')
            ->where('door_id', $door_id)
            ->first();
        $message = [
            "room_id"     => "$door->locker_id-$door->building_id",
            "message"     => $door_id,
            "locker_id"   => 1,
            "building_id" => 1,
        ];
        //$message = json_encode($message);
        sendSocketMessage('open_door', $message);
    }

    /**
     * Apertura manual desde el panel, auditada en door_event.
     * - mantenimiento / limpieza: solo abre; la entrega pendiente (si hay) sigue válida.
     * - intervencion: el casillero tiene una entrega pendiente y se libera
     *   (ver interveneDoor).
     */
    public function openManual($door_id, $tipo, $observacion)
    {
        Log::info("DoorService openManual " . jsonLog([$door_id, $tipo, $observacion]));
        $users_id = Auth::guard('api')->id();

        if ($tipo === 'intervencion') {
            return $this->interveneDoor($door_id, $users_id, $observacion);
        }

        $door_event_id = DB::table('door_event')->insertGetId([
            'door_id'     => $door_id,
            'users_id'    => $users_id,
            'tipo'        => $tipo,
            'observacion' => $observacion,
        ]);
        $this->openDoor($door_id);
        return $door_event_id;
    }

    /**
     * Libera un casillero con una entrega pendiente: registra el movimiento
     * "intervenido" (tipo 4) en el mismo id_ref y su door_event, notifica a
     * Holding, abre la puerta y pide a la tablet que resincronice.
     */
    private function interveneDoor($door_id, $users_id, $observacion)
    {
        Log::info("DoorService interveneDoor " . jsonLog([$door_id, $users_id, $observacion]));
        $entrega = DB::table('movement')
            ->select(
                'movement.movement_id',
                'movement.id_ref',
                'movement.department_id',
                'movement.building_id',
                'movement.code',
                'movement.door_id',
                'department.name as nameDepartament',
                'door_size.codigo as codigoSizeDoor',
                'controller.locker_id'
            )
            ->join('department', 'department.department_id', 'movement.department_id')
            ->join('door', 'door.door_id', 'movement.door_id')
            ->join('door_size', 'door_size.door_size_id', 'door.door_size_id')
            ->join('controller', 'controller.controller_id', 'door.controller_id')
            ->where('movement.door_id', $door_id)
            ->where('movement.type_movement_id', 1)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('movement as cierre')
                    ->whereColumn('cierre.id_ref', 'movement.id_ref')
                    ->whereIn('cierre.type_movement_id', [2, 4]);
            })
            ->orderByDesc('movement.movement_id')
            ->first();

        if (! $entrega) {
            throw new RuntimeException('El casillero no tiene una entrega pendiente');
        }

        [$movement_id, $door_event_id] = DB::transaction(function () use ($entrega, $users_id, $observacion) {
            $movement_id = DB::table('movement')->insertGetId([
                'department_id'    => $entrega->department_id,
                'door_id'          => $entrega->door_id,
                'id_ref'           => $entrega->id_ref,
                'building_id'      => $entrega->building_id,
                'code'             => $entrega->code,
                'type_movement_id' => 4,
                'users_id'         => $users_id,
                'observacion'      => $observacion,
            ]);
            $door_event_id = DB::table('door_event')->insertGetId([
                'door_id'     => $entrega->door_id,
                'users_id'    => $users_id,
                'tipo'        => 'intervencion',
                'observacion' => $observacion,
                'movement_id' => $movement_id,
            ]);
            DB::table('door')->where('door_id', $entrega->door_id)->update(['state' => 1]);
            return [$movement_id, $door_event_id];
        });
        Log::info("DoorService interveneDoor movement_id " . jsonLog($movement_id));

        $this->movementService->modificateNotificationHolding(
            $entrega->codigoSizeDoor,
            $entrega->nameDepartament,
            $entrega->door_id,
            $entrega->code,
            $entrega->id_ref,
            $movement_id,
            $entrega->building_id
        );

        $this->openDoor($door_id);
        // La tablet invalida el código del residente al recargar sus datos.
        $this->updateDatabase($entrega->locker_id);

        return $door_event_id;
    }

    public function updateDatabase($id_locker)
    {
        Log::info("DoorService updateDatabase " . jsonLog(getLocker()));
        $locker = DB::table('locker')
            ->where('locker.locker_id', $id_locker)
            ->first();
        $message = [
            "room_id"     => "$locker->locker_id-$locker->building_id",
            "message"     => $id_locker,
            "locker_id"   => 1,
            "building_id" => 1,
        ];
        sendSocketMessage('update_data_base', $message);
    }
}
