<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log
use Exception;

class LoginController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */

     public function login(LoginRequest $request)
     {
         try {
             $response = $this->apiService->post('/login', [
                 'email' => $request->email,
                 'password' => $request->password
             ]);
     
             if (!isset($response['token']) || !isset($response['user'])) {
                 throw new \Exception('Error al obtener los datos de usuario.');
             }
     
             $user = $response['user'];
             $token = $response['token'];
     
             // Consultamos nuevamente el usuario en la API para asegurarnos del estado de pago
             $userData = $this->apiService->get("/users/{$user['id']}");
     
             // Log de toda la respuesta de la API
             Log::info('Respuesta completa del usuario:', ['userData' => $userData]);
     
             // Corregir el acceso a los datos del usuario dentro de "users"
             $userData = $userData['users']; // Extraemos los datos del usuario
     
             // Comprobar si is_first_login existe y es 0 (ya pagó)
             if (isset($userData['is_first_login']) && $userData['is_first_login'] === 0) {
                 // Usuario ya ha pagado, iniciar sesión normalmente
                 Session::put('api_token', $token);
                 Session::put('user', $user);
                 $request->session()->regenerate();
     
                 Log::info("Usuario {$user['id']} ha pagado y está siendo redirigido al home.");
                 return redirect()->intended(route('home'));
             }
     
             // Si el usuario no ha pagado, redirigirlo a la pasarela de pago
             $price = match($user['registration_type']) {
                 'virtual' => "10.00",
                 default => "30.00",
             };
     
             Log::info("Usuario {$user['id']} no ha pagado, redirigiendo a PayPal.");
             return redirect()->route('paypal.pay', ['price' => $price,'userId' => $user['id']]);
     
         } catch (\Exception $e) {
             Log::error('Error en login: ' . $e->getMessage());
     
             return back()->withErrors([
                 'email' => 'Las credenciales proporcionadas son incorrectas.'
             ])->withInput($request->except('password'));
         }
     }
     


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
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

    /**
     * Handle an admin login request to the application.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function adminLogin(LoginRequest $request)
    {
        try {
            $response = $this->apiService->post('/auth/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);

            if (!isset($response['token'])) {
                throw new \Exception('No se recibió un token de la API.');
            }

            // Verificar si el usuario es administrador
            if (!$response['user']['is_admin']) {
                throw new \Exception('No tienes permisos de administrador.');
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