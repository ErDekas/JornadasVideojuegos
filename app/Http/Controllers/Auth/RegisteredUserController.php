<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ApiService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;

class RegisteredUserController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validación de los campos del formulario sin la regla unique
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // Enviar los datos del registro a la API
            $apiResponse = $this->apiService->post('/register', [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation
            ]);

            // Verificar si la API devuelve un token
            if (!isset($apiResponse['token'])) {
                throw new \Exception('Error al obtener el token de la API.');
            }

            // Almacenar el token en la sesión
            Session::put('api_token', $apiResponse['token']);

            // Redirigir al usuario al dashboard o página principal
            return redirect(route('home', absolute: false));

        } catch (\Exception $e) {
            die("Error" . $e->getMessage());
            return back()->withErrors([
                'email' => 'Hubo un problema con la API al registrar al usuario.'
            ])->withInput($request->except('password'));
        }
    }
}
