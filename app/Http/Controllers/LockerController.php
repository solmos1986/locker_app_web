<?php
namespace App\Http\Controllers;

use App\Services\LockerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LockerController extends Controller
{
    protected $lockerService;
    public function __construct(LockerService $lockerService)
    {
        $this->lockerService = $lockerService;
    }
    
    public function dataTable()
    {
        Log::info("LockerController dataTable ");
        $lockers = $this->lockerService->getLockers();
        return DataTables::of($lockers)->make(true);
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
    /**
     * Display a listing of the resource.
     */
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
