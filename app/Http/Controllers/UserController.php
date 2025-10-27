<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function dataTable(Request $request)
    {
        Log::info("UserController dataTable " . jsonLog($request->all()));
        try {
            $pageSize      = is_null($request->pageSize) ? 100 : $request->pageSize;
            $active        = is_null($request->active) ? "id" : $request->active;
            $direction     = is_null($request->direction) ? "ASC" : $request->direction;
            $pageIndex     = is_null($request->pageIndex) ? 0 : $request->pageIndex;
            $search        = is_null($request->search) ? '' : $request->search;
            $department_id = is_null($request->department_id) ? '' : $request->department_id;
            $locker_id     = is_null($request->locker_id) ? '' : $request->locker_id;
            $users         = $this->userService->dataTable($pageSize, $pageIndex, $active, $direction, $search, $department_id, $locker_id);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Get Movement',
                ],
                'data' => $users,
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'meta' => [
                    'code'    => 500,
                    'status'  => 'error',
                    'message' => 'An error has occurred!',
                ],
                'data' => null,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
