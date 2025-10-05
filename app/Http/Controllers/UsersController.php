<?php
namespace App\Http\Controllers;

use App\Services\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    protected UsersService $usersService;
    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;
    }
    /**
     * Display a listing of the resource.
     */
    public function dataTable(Request $request)
    {
        Log::info("UsersController dataTable " . jsonLog($request->all()));
        try {
            $pageSize  = is_null($request->pageSize) ? 100 : $request->pageSize;
            $active    = is_null($request->active) ? "id" : $request->active;
            $direction = is_null($request->direction) ? "ASC" : $request->direction;
            $pageIndex = is_null($request->pageIndex) ? 0 : $request->pageIndex;
            $search    = is_null($request->search) ? '' : $request->search;
            $movements = $this->usersService->dataTable($request->locker_id, $pageSize, $pageIndex, $active, $direction, $search);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Get Movement',
                ],
                'data' => $movements,
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
    public function requeriment(Request $request)
    {
        Log::info("UsersController requeriment " . jsonLog($request->all()));
        try {
            $requeriment = $this->usersService->requeriment();

            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Get requeriment',
                ],
                'data' => $requeriment,
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
 * Store a newly created resource in storage.
 */
    public function store(Request $request)
    {
        Log::info("UsersController store " . jsonLog($request->all()));
        try {
            $store = $this->usersService->storeUser(
                $request->name,
                $request->email,
                $request->celular,
                $request->password,
                $request->roles,
            );
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Registrado correctamente',
                ],
                'data' => $store,
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
 * Display the specified resource.
 */
    public function show(string $id)
    {

    }

/**
 * Show the form for editing the specified resource.
 */
    public function edit(string $id)
    {
        Log::info("UsersController edit " . jsonLog($id));
        try {
            $user = $this->usersService->editUser($id);

            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Usuario obtendido correctamente',
                ],
                'data' => $user,
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
 * Update the specified resource in storage.
 */
    public function update(Request $request, string $id)
    {
        Log::info("UsersController update " . jsonLog($request->all()));
        try {
            $update = $this->usersService->updateUser(
                $request->id,
                $request->name,
                $request->email,
                $request->celular,
                $request->password,
                $request->roles,
            );

            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Modificado correctamente',
                ],
                'data' => $update,
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
 * Remove the specified resource from storage.
 */
    public function destroy(string $id)
    {
        Log::info("UsersController destroy " . jsonLog($id));
        try {
            $delete = $this->usersService->deleteUser($id);

            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Eliminado correctamente',
                ],
                'data' => $delete,
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
}
