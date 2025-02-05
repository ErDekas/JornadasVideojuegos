<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $response = $this->apiService->post('/auth/login', $credentials);
            
            // Almacenar el token en la sesión
            Session::put('api_token', $response['token']);
            Session::put('user', $response['user']);

            return redirect()->intended(route('home'))
                           ->with('success', '¡Bienvenido/a!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas son incorrectas.'
            ])->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        if (Session::has('api_token')) {
            try {
                $this->apiService->post('/auth/logout');
            } catch (\Exception $e) {
                // Continuar con el logout aunque falle la API
            }
        }

        Session::forget(['api_token', 'user']);
        return redirect()->route('home');
    }
}