<?php
namespace App\Http\Controllers;

use App\Services\LockerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LockerController extends Controller
{
    protected $lockerService;
    public function __construct(LockerService $lockerService)
    {
        $this->lockerService = $lockerService;
    }

    public function getStatus(Request $request)
    {
        Log::info("LockerController getStatus ");
        try {
            $locker = $this->lockerService->getLockerStatus($request->locker_id);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Get locker',
                ],
                'data' => $locker,
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
        Log::info("LockerController requirement " . jsonLog($request->all()));
        try {
            $requirement = $this->lockerService->getRequirement();
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Get locker',
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
    /**
     * Display a listing of the resource.
     */

    public function detailed_movement(Request $request)
    {
        Log::info("LockerController detailed_movement " . jsonLog($request));
        try {
            $detailed_activity = $this->lockerService->detailed_movement($request->id_ref);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Get detailed movement',
                ],
                'data' => $detailed_activity,
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
        Log::info("LockerController index ");
        return view('pages.locker.index');
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
        Log::info("LockerController store " . jsonLog($request->all()));
        try {
            $locker = $this->lockerService->storeLocker(
                $request->building_id,
                $request->locker_id,
                $request->name,
                $request->address,
                $request->type_locker_id
            );
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Registrado correctamente',
                ],
                'data' => $locker,
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
        Log::info("LockerController edit " . jsonLog($id));
        try {
            $locker = $this->lockerService->editLocker($id);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Datos obtenidos correctamente',
                ],
                'data' => $locker,
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
        Log::info("LockerController update " . jsonLog($id));
        try {
            $locker = $this->lockerService->updateLocker(
                $request->locker_id,
                $request->name,
                $request->address,
                $request->type_locker_id
            );
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Modificado correctamente',
                ],
                'data' => $locker,
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
        Log::info("LockerController destroy " . jsonLog($id));
        try {
            // $locker = $this->lockerService->editLocker($id);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Eliminado correctamente',
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
}
