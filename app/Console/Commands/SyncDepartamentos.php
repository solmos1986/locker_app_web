<?php

namespace App\Console\Commands;

use App\Services\DepartmentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncDepartamentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-departamentos {building_id? : Sincroniza solo este edificio}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza los departamentos y residentes de los edificios desde la API de Holding';

    /**
     * Sincroniza los edificios con buildingName (su nombre en la API) y algún locker.
     * Los departamentos que la API no devuelve se eliminan, incluidos los cargados a
     * mano; si tienen movimientos se desactivan (ver DepartmentService::createbyApi).
     */
    public function handle(DepartmentService $departmentService)
    {
        $buildings = DB::table('building')
            ->select('building.building_id', 'building.name')
            ->whereNotNull('building.buildingName')
            ->where('building.buildingName', '<>', '')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('locker')
                    ->whereColumn('locker.building_id', 'building.building_id');
            })
            ->when($this->argument('building_id'), function ($query, $building_id) {
                $query->where('building.building_id', $building_id);
            })
            ->get();
        Log::channel('cronJob')->info("SyncDepartamentos edificios " . jsonLog($buildings));

        $fallidos = 0;
        foreach ($buildings as $building) {
            try {
                $resumen = $departmentService->createbyApi($building->building_id);
                Log::channel('cronJob')->info("SyncDepartamentos {$building->building_id} {$building->name} " . jsonLog($resumen));
                $this->info("{$building->name}: " . jsonLog($resumen));
            } catch (\Throwable $th) {
                // Un edificio con error no detiene la sincronización de los demás.
                $fallidos++;
                Log::channel('cronJob')->error("SyncDepartamentos {$building->building_id} {$building->name} " . $th->getMessage());
                $this->error("{$building->name}: " . $th->getMessage());
            }
        }

        return $fallidos === 0 ? self::SUCCESS : self::FAILURE;
    }
}
