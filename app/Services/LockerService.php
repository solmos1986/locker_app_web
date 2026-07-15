<?php
namespace App\Services;

use App\Services\AppMovil\DatabaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class LockerService
{
    protected DatabaseService $databaseService;

    public function __construct(DatabaseService $_databaseService)
    {
        $this->databaseService = $_databaseService;
    }

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
            ->join('building', 'building.building_id', 'department.building_id')
            ->join('locker', 'locker.building_id', 'building.building_id')
            ->where('locker.locker_id', $locker_id)
            ->get();

        $locker = DB::table('locker')
            ->select(
                'locker.locker_id',
                'locker.name',
                'locker.address',
                'locker.state',
                'locker.size',
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
            ->orderBy("door.order", "ASC")
        //    ->orderByRaw('FIELD(door.door_id, 1,8,2,3,7,10,4,5,6,12,13,14,15,11,9,16)')
        //->whereIn('door.door_id',[1,8,2,3,7,10,4,5,6,12,13,14,15,11,9,16])
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
        $building_id,
        $locker_id,
        $name,
        $address,
        $type_locker_id,
        $size,
        $fila,
        $columna
    ) {
        Log::info("LockerService storeLocker " . jsonLog([
            $building_id,
            $locker_id,
            $name,
            $address,
            $type_locker_id,
            $size,
            $fila,
            $columna,
        ]));

        $locker_id = DB::table('locker')->insertGetId([
            'building_id'    => $building_id,
            'type_locker_id' => $type_locker_id,
            'name'           => $name,
            'address'        => $address,
            'size'           => "$fila,$columna",
        ]);

        $token = $this->databaseService->getLocker($locker_id);

        $controller_id = DB::table('controller')->insertGetId([
            'locker_id' => $locker_id,
            'name'      => "nuevo controlador",
            'serie'     => "sssss-sss-ssss",
            'token'     => $token,
        ]);

        $this->createAutomaticDoors($fila, $columna, $controller_id);

        return $locker_id;
    }

    public function editLocker($locker_id)
    {
        Log::info("LockerService editLocker " . jsonLog($locker_id));
        $locker = DB::table('locker')
            ->select(
                'locker.building_id',
                'locker.locker_id',
                'locker.name',
                'locker.address',
                'locker.type_locker_id',
                'locker.state',
                'locker.size',
            )
            ->where(
                "locker_id", $locker_id)
            ->first();
        $size                        = explode(",", $locker->size);
        $locker->columna             = $size[0];
        $locker->fila                = $size[1];
        $locker->modificar_casillero = false;
        return $locker;
    }

    public function updateLocker(
        bool $modificar_casillero,
        $building_id,
        $locker_id,
        $name,
        $address,
        $type_locker_id,
        $size,
        $fila,
        $columna
    ) {
        Log::info("LockerService updateLocker " . jsonLog([
            $building_id,
            $locker_id,
            $name,
            $address,
            $type_locker_id,
            $size,
            $fila,
            $columna,
        ]));

        $update = DB::table('locker')
            ->where('locker.locker_id', $locker_id)->update([
            'type_locker_id' => $type_locker_id,
            'name'           => $name,
            'address'        => $address,
            'size'           => $size,
        ]);

        $controller = DB::table('controller')
            ->where('controller.locker_id', $locker_id)
            ->first();

        if ($modificar_casillero) {
            $this->removeAutomaticDoors($controller->controller_id);
            $this->createAutomaticDoors($fila, $columna, $controller->controller_id);
        }
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

    private function removeAutomaticDoors($controller_id)
    {
        $deleteResponseComand = DB::table('response_comand')
            ->join('request_comand', 'request_comand.request_comand_id', 'response_comand.request_comand_id')
            ->join('door', 'door.door_id', 'request_comand.door_id')
            ->where('door.controller_id', $controller_id)
            ->delete();
        $deleteRequestComand = DB::table('request_comand')
            ->join('door', 'door.door_id', 'request_comand.door_id')
            ->where('door.controller_id', $controller_id)
            ->delete();
        $deleteDoor = DB::table('door')
            ->where('door.controller_id', $controller_id)
            ->delete();
    }

    private function createAutomaticDoors(
        $fila,
        $columna,
        $controller_id
    ) {

        $limit = $fila * $columna;
        Log::info("LockerService limit " . jsonLog($limit));
        foreach (getDoor() as $key => $door) {
            if ($key > $limit - 1) {
                break;
            }

            $door_id = DB::table('door')
                ->insertGetId([
                    'door_size_id'  => 1,
                    'controller_id' => $controller_id,
                    'name'          => $door['name'],
                    'order'         => $door['order'],
                ]);
        }

        $doors = DB::table('door')
            ->where('controller_id', $controller_id)
            ->get();

        foreach ($doors as $i => $door) {
            $open = DB::table('request_comand')
                ->insertGetId([
                    'door_id' => $door->door_id,
                    'comand'  => getComandOpen()[$i]['comand'],
                    'name'    => getComandOpen()[$i]['name'],
                ]);
            $read = DB::table('request_comand')
                ->insertGetId([
                    'door_id' => $door->door_id,
                    'comand'  => getComandRead()[$i]['comand'],
                    'name'    => getComandRead()[$i]['name'],
                ]);
        }

        $request_opened = DB::table('request_comand')
            ->join('door', 'door.door_id', 'request_comand.door_id')
            ->where('door.controller_id', $controller_id)
            ->where('request_comand.name', 'abrir')
            ->get();

        foreach ($request_opened as $i => $request_opened) {
            $response_comand_id = DB::table('response_comand')
                ->insertGetId([
                    'request_comand_id' => $request_opened->request_comand_id,
                    'comand'            => getComandOpen()[$i]['comand'],
                    'name'              => getComandOpen()[$i]['name'],
                ]);
        }

        $request_readeds = DB::table('request_comand')
            ->join('door', 'door.door_id', 'request_comand.door_id')
            ->where('door.controller_id', $controller_id)
            ->where('request_comand.name', 'lectura')
            ->get();

        foreach ($request_readeds as $i => $request_readed) {
            $response_opened = DB::table('response_comand')
                ->insertGetId([
                    'request_comand_id' => $request_readed->request_comand_id,
                    'comand'            => getComandOpened()[$i]['comand'],
                    'name'              => getComandOpened()[$i]['name'],
                ]);
            $response_closed = DB::table('response_comand')
                ->insertGetId([
                    'request_comand_id' => $request_readed->request_comand_id,
                    'comand'            => getComandClosed()[$i]['comand'],
                    'name'              => getComandClosed()[$i]['name'],
                ]);
        }

    }
}
