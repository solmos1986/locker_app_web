<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class ControllerService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function dataTable($locker_id, $pageSize, $pageIndex, $active, $direction, $search)
    {
        Log::info("ControllerService dataTable " . jsonLog([$locker_id, $pageSize, $pageIndex, $active, $direction, $search]));
        Log::info("ControllerService getUser()->get('client_id') " . jsonLog(getUser()->get('client_id')));
        $dataTable   = new stdClass();
        $controllers = DB::table('controller')
            ->where('controller.locker_id', $locker_id)
            ->orderBy($active, $direction)
            ->paginate($pageSize);

        $dataTable->paginate = [
            'length'    => $controllers->count(),
            'pageIndex' => $pageIndex,
            'pageSize'  => $pageSize,
        ];
        $dataTable->sort = [
            'active'    => $active,
            'direction' => $direction,
        ];
        $dataTable->controllers = $controllers->items();
        return $dataTable;
    }

    public function editController(int $controller_id)
    {
        Log::info("ControllerService dataTable " . jsonLog([$controller_id]));
        Log::info("ControllerService getLocker() " . jsonLog(getLocker()));

        $locker = DB::table('locker')
            ->join('controller', 'controller.locker_id', 'locker.locker_id')
            ->where('locker.locker_id', $controller_id)
            ->first();

        $controller = DB::table('controller')
            ->select(
                'controller.controller_id',
                'controller.locker_id',
                'controller.name',
                'controller.serie',
                'controller.token'
            )
            ->where('controller.controller_id', $controller_id)
            ->first();
        $controller->building_id = $locker->building_id;

        return $controller;
    }

    public function updateController(int $controller_id, string $name, string $serie)
    {
        Log::info("ControllerService dataTable " . jsonLog([$controller_id]));

        $controller = DB::table('controller')
            ->where('controller.controller_id', $controller_id)
            ->update([
                'name'  => $name,
                'serie' => $serie,
            ]);

        return $controller;
    }
}
