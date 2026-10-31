<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class DepartmentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function dataTable($pageSize, $pageIndex, $active, $direction, $search, $locker_id)
    {
        Log::info("DepartmentService dataTable " . jsonLog([$pageSize, $pageIndex, $active, $direction, $search, $locker_id]));
        $dataTable   = new stdClass();
        $departments = DB::table('department')
            ->select(
                'building.name as name_builder',
                'department.department_id',
                'department.building_id',
                'department.name',
                'department.state'
            )
            ->join('building', 'building.building_id', 'department.building_id')
            ->join('locker', 'locker.building_id', 'building.building_id')
            ->where('locker.locker_id', $locker_id)
            ->orderBy($active, $direction)
            ->paginate($pageSize, ['*'], null, ($pageIndex + 1));

        foreach ($departments->items() as $key => $department) {
            $department->users = DB::table('user')
                ->select(
                    'user.department_id',
                    'user.user_id',
                    'user.name',
                    'user.celular',
                    'user.state',
                )
                ->where('user.department_id', $department->department_id)
                ->get();
        }

        $locker = DB::table('locker')
            ->where('locker_id', $locker_id)
            ->first();

        $dataTable->paginate = [
            'length'    => $departments->total(),
            'pageIndex' => $pageIndex,
            'pageSize'  => $pageSize,
        ];
        $dataTable->sort = [
            'active'    => $active,
            'direction' => $direction,
        ];
        $dataTable->departments = $departments->items();
        $dataTable->locker      = $locker;
        return $dataTable;
    }

    public function dataTableManager($pageSize, $pageIndex, $active, $direction, $search, $building_id)
    {
        Log::info("DepartmentService dataTableManager " . jsonLog([$pageSize, $pageIndex, $active, $direction, $search, $building_id]));
        $dataTable    = new stdClass();
        $departaments = DB::table('department')
            ->select(
                'department.department_id',
                'department.building_id',
                'department.name',
                'department.is_api',
                'department.state'
            )
            ->where('department.building_id', $building_id)
            ->orderBy($active, $direction)
            ->paginate($pageSize, ['*'], null, ($pageIndex + 1));

        foreach ($departaments->items() as $key => $department) {
            $department->users = DB::table('user')
                ->select(
                    'user.department_id',
                    'user.user_id',
                    'user.name',
                    'user.celular',
                    'user.state',
                )
                ->where('user.department_id', $department->department_id)
                ->get();
        }

        $dataTable->paginate = [
            'length'    => $departaments->total(),
            'pageIndex' => $pageIndex,
            'pageSize'  => $pageSize,
        ];
        $dataTable->departaments = $departaments->items();
        $dataTable->sort         = [
            'active'    => $active,
            'direction' => $direction,
        ];
        return $dataTable;
    }

    public function createbyApi($building_id)
    {
        $departaments = $this->getDepatamentResidentesByApi($building_id);
        if (empty($departaments)) {
            // Una respuesta vacía de la API eliminaría todos los departamentos del edificio.
            throw new \RuntimeException("La API no devolvió departamentos para el edificio {$building_id}; no se modificó nada");
        }
        $resumen      = DB::transaction(function () use ($departaments, $building_id) {
            return $this->insertDepartamentResident($departaments, $building_id);
        });
        Log::info("DepartmentService createbyApi resumen " . jsonLog($resumen));

        // Solo si cambió algún departamento: la sincronización automática corre cada 5 minutos
        // y cada aviso hace que la tablet descargue de nuevo toda su BD.
        if (array_sum($resumen) > 0) {
            $this->notificarLockers($building_id);
        }
        return $resumen;
    }

    /**
     * Avisa a las tablets del edificio que resincronicen su BD local; de lo contrario
     * seguirían ofreciendo departamentos que ya no existen en el servidor.
     */
    private function notificarLockers($building_id)
    {
        $lockers = DB::table('locker')
            ->where('locker.building_id', $building_id)
            ->get();

        foreach ($lockers as $locker) {
            sendSocketMessage('update_data_base', [
                "room_id"     => "$locker->locker_id-$locker->building_id",
                "message"     => $locker->locker_id,
                "locker_id"   => $locker->locker_id,
                "building_id" => $locker->building_id,
            ]);
        }
    }

    private function getDepatamentResidentesByApi($building_id)
    {
        // Sin getUser(): también se ejecuta desde consola (app:sync-departamentos), donde no hay JWT.
        Log::info("DepartmentService/getDepatamentResidentesByApi " . jsonLog($building_id));

        try {

            $building = DB::table('building')
                ->where('building_id', $building_id)
                ->first();

            if (is_null($building)) {
                throw new \RuntimeException("Edificio {$building_id} no encontrado");
            }

            $client = new \GuzzleHttp\Client();
            $url    = env("URL_APP_EXPERIENCE") . "/api/v1/building/department-list/" . $building->buildingName . "?all=true";
            Log::info("DepartmentService/getDepatamentResidentesByApi url " . jsonLog($url));
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env("TOKEN_EXPERIENCE"),
                    'Content-Type'  => 'application/json',
                    'Accept'        => '*/*',
                ],
            ]);
            Log::info("DepartmentService/getDepatamentResidentesByApi status " . jsonLog($response->getStatusCode()));
            Log::info("DepartmentService/getDepatamentResidentesByApi response " . jsonLog(json_decode($response->getBody()->getContents(), true)));
            $data = json_decode($response->getBody());

            $departaments = [];

            foreach ($data->data->department_list as $key => $department) {
                $users = [];
                foreach ($department->residents as $key => $resident) {
                    $users[] = [
                        'name'         => strtolower($resident->name),
                        'celular'      => $resident->phone,
                        'has_whatsapp' => 0,
                        'id_ref'       => $department->departmentId,
                    ];
                }
                $departaments[] = [
                    'id_ref' => $department->departmentId,
                    'name'   => strtolower($department->departmentName),
                    'is_api' => 'si',
                    'users'  => $users,
                ];
            }
            Log::info("DepartmentService/getDepatamentResidentesByApi departaments " . jsonLog($departaments));
            return $departaments;
        } catch (\Throwable $th) {
            Log::error($th);
            Log::error("DepartmentService/getDepatamentResidentesByApi error al notificar al servidor");
            throw new \RuntimeException("No se pudo obtener los departamentos desde la API externa: " . $th->getMessage(), 0, $th);
        }
    }

    /**
     * Reemplaza los departamentos del edificio por los que trae la API:
     * - los que coinciden por id_ref se actualizan y reactivan;
     * - los nuevos se crean;
     * - los que ya no vienen en la API se eliminan, salvo que tengan movimientos:
     *   esos se desactivan (state = 0), porque borrarlos eliminaría en cascada
     *   sus entregas/retiros (FK movement.department_id ON DELETE CASCADE).
     */
    private function insertDepartamentResident($departaments, $building_id)
    {
        Log::info("DepartmentService insertDepartamentResident " . jsonLog($departaments));
        $sincronizados = [];
        $resumen       = ['actualizados' => 0, 'creados' => 0, 'eliminados' => 0, 'desactivados' => 0];

        foreach ($departaments as $key => $departament) {
            $vefied_department = DB::table('department')
                ->where('department.id_ref', $departament['id_ref'])
                ->where('department.building_id', $building_id)
                ->first();

            if ($vefied_department != null) {
                Log::info("DepartmentService modificar " . jsonLog($vefied_department));
                // MySQL devuelve las filas que cambiaron, no las que coincidieron.
                $cambios = DB::table('department')
                    ->where('department.department_id', $vefied_department->department_id)
                    ->update([
                        'name'   => $departament['name'],
                        'is_api' => $departament['is_api'],
                        'state'  => 1,
                    ]);
                $this->insertResidente($departament['users'], $vefied_department->department_id);
                $sincronizados[] = $vefied_department->department_id;
                $resumen['actualizados'] += $cambios;
            } else {
                Log::info("DepartmentService insert " . jsonLog($vefied_department));
                $insert_department = DB::table('department')
                    ->insertGetId([
                        'name'        => $departament['name'],
                        'id_ref'      => $departament['id_ref'],
                        'is_api'      => $departament['is_api'],
                        'building_id' => $building_id,
                    ]);
                $this->insertResidente($departament['users'], $insert_department);
                $sincronizados[] = $insert_department;
                $resumen['creados']++;
            }

        }

        $sobrantes = DB::table('department')
            ->select('department.department_id')
            ->selectRaw('EXISTS (SELECT 1 FROM movement WHERE movement.department_id = department.department_id) AS con_movimientos')
            ->where('department.building_id', $building_id)
            ->whereNotIn('department.department_id', $sincronizados)
            ->get();
        Log::info("DepartmentService departamentos sobrantes " . jsonLog($sobrantes));

        $eliminar    = $sobrantes->where('con_movimientos', 0)->pluck('department_id');
        $desactivar  = $sobrantes->where('con_movimientos', 1)->pluck('department_id');

        if ($eliminar->isNotEmpty()) {
            // Sus residentes (user) se eliminan en cascada.
            DB::table('department')->whereIn('department_id', $eliminar)->delete();
        }
        if ($desactivar->isNotEmpty()) {
            DB::table('department')->whereIn('department_id', $desactivar)->update(['state' => 0]);
        }
        $resumen['eliminados']   = $eliminar->count();
        $resumen['desactivados'] = $desactivar->count();

        return $resumen;
    }
    private function insertResidente($users, $department_id)
    {
        $delete = DB::table('user')->where('user.department_id', $department_id)->delete();
        foreach ($users as $key => $user) {
            $caracteres      = ["-", "+"];
            $user['celular'] = str_replace($caracteres, "", $user['celular']);

            $insert_user = DB::table('user')
                ->insertGetId([
                    'name'          => $user['name'],
                    'celular'       => $user['celular'],
                    'has_whatsapp'  => '0',
                    'department_id' => $department_id,
                ]);
        }
    }

    public function editDepartament($department_id)
    {
        Log::info("DepartmentService editDepartament " . jsonLog($department_id));
        $department = DB::table('department')
            ->select(
                'department.department_id',
                'department.building_id',
                'department.name',
                'department.is_api',
                'department.id_ref',
                'department.state',
            )
            ->where('department.department_id', $department_id)
            ->first();
        $users = DB::table('user')
            ->select(
                'user.user_id',
                'user.department_id',
                'user.name',
                'user.celular',
                'user.state',
            )
            ->where('user.department_id', $department_id)->get();
        $department->users = $users;
        return $department;
    }

    public function updateDepartament(
        $department_id,
        $building_id,
        $name,
        $is_api,
        $id_ref,
        $state
    ) {
        Log::info("DepartmentService updateDepartament " . jsonLog([
            $department_id,
            $building_id,
            $name,
            $is_api,
            $id_ref,
            $state,
        ]));
        $department = DB::table('department')
            ->where('department.department_id', $department_id)
            ->update(
                [
                    "name"   => $name,
                    "is_api" => $is_api,
                    "id_ref" => $id_ref,
                    "state"  => $state,
                ]
            );
        return $department;
    }
}
