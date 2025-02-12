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
            $response = $this->apiService->post('/login', $credentials);
            
            if (!isset($response['token'])) {
                throw new \Exception('No se recibió un token de la API.');
            }

            // Guardar en sesión
            Session::put('api_token', $response['token']);
            Session::put('user', $response['user']);

            return redirect()->intended(route('home'))
                           ->with('success', '¡Bienvenido/a!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => $e->getMessage() ?: 'Las credenciales proporcionadas son incorrectas.'
            ])->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        die("he entrado al login");
        if (Session::has('api_token')) {
            try {
                $this->apiService->post('/auth/logout', [], [
                    'Authorization' => 'Bearer ' . Session::get('api_token')
                ]);
            } catch (\Exception $e) {
                // Ignorar errores de la API y continuar con el logout local
            }
        }

        Session::forget(['api_token', 'user']);
        return redirect()->route('home');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $response = $this->apiService->post('/auth/login', $credentials);

            if (!isset($response['token'])) {
                throw new \Exception('No se recibió un token de la API.');
            }

            // Verificar si el usuario es administrador
            if (!$response['user']['is_admin']) {
                return back()->withErrors([
                    'email' => 'No tienes permisos de administrador.'
                ]);
            }

            Session::put('api_token', $response['token']);
            Session::put('user', $response['user']);

            return redirect()->intended(route('admin'))
                           ->with('success', '¡Bienvenido admin!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => $e->getMessage() ?: 'Las credenciales proporcionadas son incorrectas.'
            ])->withInput($request->except('password'));
        }
    }
}
