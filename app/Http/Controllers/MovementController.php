<?php
namespace App\Http\Controllers;

use App\Services\MovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class MovementController extends Controller
{
    private MovementService $movementService;
    public function __construct(MovementService $_movementService)
    {
        $this->movementService = $_movementService;
    }

    public function dataTable()
    {
        Log::info("MovementController dataTable ");
        $movements = $this->movementService->getMovimientos();
        return DataTables::of($movements)->make(true);
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
        Log::info("MovementService store " . jsonLog($request->all()));
        try {
            $this->movementService->storeMovement($request->user_id, $request->door_id, $request->code);
            return response()->json([
                "status" => "ok",
            ]);
        } catch (\Throwable $th) {
            Log::error("message");("MovementService store " . jsonLog($th));
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
