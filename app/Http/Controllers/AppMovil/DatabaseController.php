<?php
namespace App\Http\Controllers\AppMovil;

use App\Http\Controllers\Controller;
use App\Models\Locker;
use App\Services\AppMovil\DatabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class DatabaseController extends Controller
{
    protected $databaseService;
    public function __construct(DatabaseService $_databaseService)
    {
        $this->databaseService = $_databaseService;
    }
    /**
     * Display a listing of the resource.
     */
    public function dataBase()
    {
        Log::info("DatabaseController dataBase()");
        $movimientos = $this->databaseService->GetDataBase();
        return response()->json($movimientos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createToken(Request $request)
    {
        Log::info("DatabaseController createToken " . jsonLog($request->all()));
        try {
            $locker = $this->databaseService->getLocker($request->locker_id);
            Log::info("DatabaseController locker " . jsonLog($locker));
            $customClaims = [
                "locker_id"      => $locker->locker_id,
                "building_id"    => $locker->building_id,
                "name"           => $locker->name,
                "address"        => $locker->address,
                "type_locker_id" => $locker->type_locker_id,
                "state"          => $locker->state,
            ];
            $token = JWTAuth::customClaims($customClaims)->fromUser($locker, $customClaims);

            Log::info("AuthController/login regenerateToken " . jsonLog($token));

            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'OK',
                    'message' => 'Token generado correctamente',
                ],
                'data' => [
                    'token' => $token,
                ],
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

    public function getToken(Request $request)
    {
        Log::info("DatabaseController getToken " . jsonLog($request->all()));
        try {
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'Usuario extraidos',
                    'message' => 'Datos validos',
                ],
                'data' => getLocker()->get(),
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
