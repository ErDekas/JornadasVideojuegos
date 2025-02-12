<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\ApiService;
use Illuminate\Support\Facades\Session;



class AuthenticatedSessionController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->middleware('guest')->except('logout');
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        try {
            //die("auth");
            $response = $this->apiService->post('/login', $credentials);

            if (!isset($response['token']) || !isset($response['user'])) {
                throw new \Exception('Error al obtener los datos de usuario.');
            }

            Session::put('api_token', $response['token']);
            Session::put('user', $response['user']);

            $request->session()->regenerate();

            return redirect()->intended(route('home', absolute: false));

        } catch (\Exception $e) {
            die("Error" . $e->getMessage());
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas son incorrectas.'
            ])->withInput($request->except('password'));
        }
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Cerrar sesión de manera explícita
        Auth::logout();

        // Eliminar el token y los datos del usuario de la sesión
        Session::forget('api_token');
        Session::forget('user');

        // Invalidar la sesión y regenerar el token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Redirige a la página principal
    }


}
