<?php
namespace App\Http\Controllers;

use App\Services\MovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MovementController extends Controller
{
    protected $movementService;
    public function __construct(
        MovementService $movementService
    ) {
        $this->movementService = $movementService;
    }

    public function dataTable(Request $request)
    {
        Log::info("MovementController dataTable " . jsonLog($request->all()));

        try {
            $pageSize  = is_null($request->pageSize) ? 100 : $request->pageSize;
            $active    = is_null($request->active) ? "id_empresa" : $request->active;
            $direction = is_null($request->direction) ? "ASC" : $request->direction;
            $pageIndex = is_null($request->pageIndex) ? 0 : $request->pageIndex;
            $search    = is_null($request->search) ? '' : $request->search;
            $movements = $this->movementService->dataTable($pageSize, $pageIndex, $active, $direction, $search);
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
     * Display a listing of the resource.
     */
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
        Log::info("MovementController store " . jsonLog($request->all()));
        try {
            $this->movementService->storeMovement($request->user_id, $request->door_id, $request->code);
            return response()->json([
                "status" => "ok",
            ]);
        } catch (\Throwable $th) {
            Log::error("MovementController store " . jsonLog($th));
            return response()->json([
                "status" => "error",
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        Log::info("MovementService update ($request->movement_id)");
        try {
            $this->movementService->updateMovement($request->movement_id);
            return response()->json([
                "status" => "ok",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
