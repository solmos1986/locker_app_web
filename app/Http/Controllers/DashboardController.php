<?php
namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function info()
    {
        Log::info("DashboardController info ");
        try {
            $info = $this->dashboardService->info();
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Get dashboard',
                ],
                'data' => $info,
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
