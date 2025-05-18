<?php

namespace App\Http\Controllers\AppMovil;

use App\Http\Controllers\Controller;
use App\Services\AppMovil\DatabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DatabaseController extends Controller
{
    private DatabaseService $databaseService;
    public function __construct(DatabaseService $_databaseService)
    {
        $this->databaseService = $_databaseService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info("DatabaseController index()");
        $movimientos = $this->databaseService->GetDataBase();
        return response()->json($movimientos);
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
