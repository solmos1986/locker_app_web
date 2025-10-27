<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function dataTable($pageSize, $pageIndex, $active, $direction, $search, $department_id, $locker_id)
    {
        Log::info("UserService dataTable " . jsonLog([$pageSize, $pageIndex, $active, $direction, $search, $department_id, $locker_id]));
        $dataTable = new stdClass();
        $users     = DB::table('user')
            ->select(
                'user.department_id',
                'user.user_id',
                'user.name',
                'user.celular',
                'user.state',
            )
            ->where('user.department_id', $department_id)
            ->orderBy($active, $direction)
            ->paginate($pageSize);
        $locker = DB::table('locker')
            ->where('locker_id', $locker_id)
            ->first();
        $dataTable->paginate = [
            'length'    => $users->count(),
            'pageIndex' => $pageIndex,
            'pageSize'  => $pageSize,
        ];
        $dataTable->sort = [
            'active'    => $active,
            'direction' => $direction,
        ];
        $dataTable->users  = $users->items();
        $dataTable->locker = $locker;
        return $dataTable;
    }
}
