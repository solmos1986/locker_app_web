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
        $dataTable = new stdClass();
        $controllers   = DB::table('controller')
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

}
