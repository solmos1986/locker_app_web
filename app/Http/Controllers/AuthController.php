<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $loginService;
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }
    /**
     * Display a listing of the resource.
     */

    public function login(Request $request)
    {
        Log::info("AuthController/login " . jsonLog($request->all()));
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $empresa = $this->loginService->getCLientDefault($user->id);

                $customClaims = ['client_id' => $empresa->client_id];
                Log::info("AuthController/login customClaims " . jsonLog([$user, $customClaims]));
                $token = JWTAuth::customClaims($customClaims)->fromUser($user, $customClaims);
                Log::info("AuthController/login regenerateToken " . jsonLog($token));
            } else {
                return response()->json([
                    'meta' => [
                        'code'    => 401,
                        'status'  => 'Error',
                        'message' => 'Datos invalidos',
                    ],
                    'data' => null,
                ]);
            }
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Bienvenido',
                ],
                'data' => [
                    "auth"  => $user,
                    "rol"   => $user->getCurrentRol,
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
    public function logout()
    {
        Log::info("AuthController logout " . jsonLog(Auth::user()));
        Auth::logout();
        return view('pages.auth.login');
    }

    public function getUser(Request $request)
    {
        Log::info("AuthController/getUser " . jsonLog($request->all()));
        try {
            return response()->json([
                'meta' => [
                    'code'    => 200,
                    'status'  => 'success',
                    'message' => 'Usuario valido',
                ],
                'data' => [
                    "auth" => Auth::user(),
                    "rol"  => Auth::user()->getCurrentRol,
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
    /**
     * Display a listing of the resource.
     */
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
