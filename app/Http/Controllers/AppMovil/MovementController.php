<?php
namespace App\Http\Controllers\AppMovil;

use App\Http\Controllers\Controller;
use App\Services\AppMovil\DatabaseService;
use App\Services\AppMovil\MovementAppMovilService;
use App\Services\AppMovil\MovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MovementController extends Controller
{
    protected $databaseService;
    protected $movementService;
    protected $movementAppMovilService;
    /**
     * Display a listing of the resource.
     */
    public function __construct(
        MovementService $movementService,
        MovementAppMovilService $movementAppMovilService,
        DatabaseService $databaseService
    ) {
        $this->movementService         = $movementService;
        $this->movementAppMovilService = $movementAppMovilService;
        $this->databaseService         = $databaseService;
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
        Log::info("MovementController AppMovil store " . jsonLog($request->all()));
        try {
            $this->databaseService->GetDataBase();
            //$this->movementAppMovilService->storeMovement($request->user_id, $request->door_id, $request->code);
            $this->movementService->storeMovement($request->user_id, $request->door_id, $request->code);

            return response()->json([
                "status" => "ok",
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
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
