<?php

namespace App\Http\Controllers;

use App\Services\ChangeCompanyService;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChangeCompanyController extends Controller
{
    private ChangeCompanyService $changeCompanyService;
    private LoginService $loginService;

    public function __construct(ChangeCompanyService $_changeCompanyService, LoginService $_loginService)
    {
        $this->changeCompanyService = $_changeCompanyService;
        $this->loginService = $_loginService;
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
        Log::info("ChangeCompanyController change " . jsonLog($request->all()));
        $empresa = $this->loginService->getChangeCompany(Auth::user()->id, $request->client_id);
        $request->session()->put('client_id', $empresa->client_id);
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
