<?php
namespace App\Http\Controllers;

use App\Services\ResidentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ResidentController extends Controller
{
    protected ResidentService $residentService;
    public function __construct(ResidentService $residentService)
    {
        $this->residentService = $residentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info("ResidentController index ");
        return view('pages.resident.index');
    }
    public function dataTable()
    {
        Log::info("ResidentController dataTable ");
        $movements = $this->residentService->getResidents();
        return DataTables::of($movements)->make(true);
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
        try {
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'ok',
                    'message' => 'Registrado correctamente',
                ],
                'data' => null,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'meta' => [
                    'code'    => 500,
                    'status'  => 'error',
                    'message' => 'A ocurrido un error',
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
        Log::info("ResidentController edit " . jsonLog($id));
        try {
            $residente = $this->residentService->editResident($id);
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'ok',
                    'message' => 'Registrado correctamente',
                ],
                'data' => $residente,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'meta' => [
                    'code'    => 500,
                    'status'  => 'error',
                    'message' => 'A ocurrido un error',
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
