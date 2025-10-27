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
    public function pending(Request $request)
    {
        Log::info("MovementController AppMovil pending " . jsonLog($request->all()));

        try {
            $this->movementService->storeMovement($request->department_id, $request->door_id, $request->code, $request->id_ref);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'OK',
                    'message' => 'Guardado correctamente',
                ],
                'data' => null,
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function received(string $id, Request $request)
    {
        Log::info("MovementService received " . jsonLog($request->movement_id));

        try {
            $this->movementService->updateMovement($request->movement_id);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'OK',
                    'message' => 'Guardado correctamente',
                ],
                'data' => null,
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
        //
    }
}
