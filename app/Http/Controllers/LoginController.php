<?php
namespace App\Http\Controllers;

use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected LoginService $loginService;
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }
    public function index()
    {
        $new_password = Hash::make('123456789');
        //Log::info("LoginController verificate " . jsonLog($new_password));
        return view('pages.auth.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function verificate(Request $request)
    {
        //Log::info("LoginController verificate " . jsonLog($request->all()));

        //$new_password = Hash::make($request->password);

        if (Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            $company = $this->loginService->getCompany(Auth::user()->id);
            $getRolesBuilding = $this->loginService->getRolesBuilding(Auth::user()->id);
            $request->session()->put('company_id', $company->company_id);
            //$request->session()->put('rol', $getRolesBuilding->company_id);
            $request->session()->regenerate();
            return redirect()->route('dashboard.index');
        }
        Log::info("LoginController enviar Correo no valido ");
        return back()->withErrors([
            'email' => 'Correo no valido.',
        ])->onlyInput('email');
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
