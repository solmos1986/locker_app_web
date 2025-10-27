<?php
namespace App\Http\Controllers;

use App\Services\ApiIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiIntegrationController extends Controller
{
    protected ApiIntegrationService $apiIntegration;
    public function __construct(ApiIntegrationService $apiIntegration)
    {
        $this->apiIntegration = $apiIntegration;
    }
    /**
     * Display a listing of the resource.
     */
    public function dataTable(Request $request)
    {
        Log::info("ApiIntegrationController dataTable " . jsonLog($request->all()));
        try {
            $pageSize    = is_null($request->pageSize) ? 100 : $request->pageSize;
            $active      = is_null($request->active) ? "id_empresa" : $request->active;
            $direction   = is_null($request->direction) ? "ASC" : $request->direction;
            $pageIndex   = is_null($request->pageIndex) ? 0 : $request->pageIndex;
            $search      = is_null($request->search) ? '' : $request->search;
            $building_id = is_null($request->building_id) ? '' : $request->building_id;
            $building    = $this->apiIntegration->dataTable($pageSize, $pageIndex, $active, $direction, $building_id, $search);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Datos obtenidos correctamente',
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
