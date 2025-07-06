<?php

namespace App\Http\Controllers;

use App\Services\ChangeCompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChangeCompanyController extends Controller
{
    private ChangeCompanyService $changeCompanyService;
    public function __construct(ChangeCompanyService $_changeCompanyService)
    {
        $this->changeCompanyService = $_changeCompanyService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info("ChangeCompanyController index ");
        $companies = $this->changeCompanyService->changeCompany();
        return view('pages.change-company.index', compact('companies'));
    }

    public function change(Request $request)
    {
        Log::info("ChangeCompanyController change ".jsonLog($request->all()));
        return redirect()->route('dashboard.index');
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
