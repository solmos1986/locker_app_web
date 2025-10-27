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
                'department.department_id',
                'department.building_id',
                'department.name',
                'department.state'
            )
            ->join('building', 'building.building_id', 'department.building_id')
            ->join('locker', 'locker.building_id', 'building.building_id')
            ->where('locker.locker_id', $locker_id)
            ->orderBy($active, $direction)
            ->paginate($pageSize);

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
            'length'    => $departments->count(),
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
}
