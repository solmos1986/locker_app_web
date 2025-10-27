<?php
namespace App\Http\Controllers;

use App\Services\BuildingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BuildingController extends Controller
{
    protected BuildingService $buildingService;
    public function __construct(BuildingService $buildingService)
    {
        $this->buildingService = $buildingService;
    }
    /**
     * Display a listing of the resource.
     */
    public function dashBoardBuilding(Request $request)
    {
        Log::info("BuildingController dashBoard " . jsonLog($request->all()));
        try {
            $building = $this->buildingService->listBuild();
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Get Movement',
                ],
                'data' => $building,
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
    public function dashBoardLocker(Request $request)
    {
        Log::info("BuildingController dashBoard " . jsonLog($request->all()));
        try {
            $building = $this->buildingService->bluildingStatus($request->building_id);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Get Movement',
                ],
                'data' => $building,
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
        Log::info("BuildingController store " . jsonLog($request->all()));
        try {
            $store = $this->buildingService->store(
                $request->building_id,
                $request->company_id,
                $request->name,
                $request->code,
                $request->address,
                $request->manager,
                $request->phone,
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        Log::info("BuildingController edit " . jsonLog($request->all()));
        try {

            $building = $this->buildingService->edit($id);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Obtenido correctamente',
                ],
                'data' => $building,
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
        Log::info("BuildingController store " . jsonLog($request->all()));
        try {
            $store = $this->buildingService->update(
                $request->building_id,
                $request->company_id,
                $request->name,
                $request->code,
                $request->address,
                $request->manager,
                $request->phone,
            );
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Modificado correctamente',
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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        Log::info("BuildingController destroy " . jsonLog($request->all()));
        try {

            $building = $this->buildingService->delete($id);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Eliminado correctamente',
                ],
                'data' => $building,
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
