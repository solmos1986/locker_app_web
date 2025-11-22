<?php
namespace App\Http\Controllers;

use App\Services\DoorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoorController extends Controller
{
    protected DoorService $doorService;
    public function __construct(DoorService $doorService)
    {
        $this->doorService = $doorService;
    }
    /**
     * Display a listing of the resource.
     */

    public function dataTable(Request $request)
    {
        Log::info("ControllerController dataTable " . jsonLog($request->all()));
        try {
            $pageSize  = is_null($request->pageSize) ? 100 : $request->pageSize;
            $active    = is_null($request->active) ? "controller_id" : $request->active;
            $direction = is_null($request->direction) ? "ASC" : $request->direction;
            $pageIndex = is_null($request->pageIndex) ? 0 : $request->pageIndex;
            $search    = is_null($request->search) ? '' : $request->search;
            $movements = $this->doorService->dataTable($request->locker_id, $pageSize, $pageIndex, $active, $direction, $search);
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

    public function requirement(Request $request)
    {
        Log::info("ControllerController dataTable " . jsonLog($request->all()));
        try {
            $requirement = $this->doorService->requirement($request->locker_id);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'requeriments extraido',
                ],
                'data' => $requirement,
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

    public function index()
    {
        //
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
        Log::info("ControllerController store " . jsonLog($request->all()));
        try {
            $door = $this->doorService->storeDoor(
                $request->door_id,
                $request->door_size_id,
                $request->controller_id,
                $request->name,
                $request->state,
                $request->order,
            );
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Registrado correctamente',
                ],
                'data' => $door,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function open(string $id)
    {
        Log::info("ControllerController open " . jsonLog($id));
        try {
            $door = $this->doorService->openDoor(
                $id,
            );
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Solicitud de apertura enviada',
                ],
                'data' => $door,
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
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        Log::info("ControllerController edit " . jsonLog($request->all([])));
        try {
            $door = $this->doorService->editeDoor(
                $id,
            );
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Door extraido correctamente',
                ],
                'data' => $door,
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
        Log::info("ControllerController update " . jsonLog($request->all()));
        try {
            $door = $this->doorService->updateDoor(
                $request->door_id,
                $request->door_size_id,
                $request->controller_id,
                $request->name,
                $request->state,
                $request->order,
            );
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Modificado correctamente',
                ],
                'data' => $door,
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
        Log::info("ControllerController destroy " . jsonLog($id));
        try {
            $door = $this->doorService->deleteDoor(
                $id,
            );
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Eliminado correctamente',
                ],
                'data' => $door,
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
