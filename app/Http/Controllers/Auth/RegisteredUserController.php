<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
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
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        try {
            // Los datos ya están validados por el FormRequest
            $validatedData = $request->validated();

            // Enviar los datos del registro a la API
            $apiResponse = $this->apiService->post('/register', [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'password_confirmation' => $validatedData['password_confirmation'],
                'tipo_inscripcion' => $validatedData['tipo_inscripcion'],
                'certificado_alumno' => $validatedData['certificado_alumno'] ?? false,
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
            // Mejorar el manejo de errores para incluir logging
            var_dump('Error en registro de usuario: ' . $e->getMessage());
            
            return back()->withErrors([
                'email' => 'Hubo un problema con la API al registrar al usuario.'
            ])->withInput($request->except('password'));
        }
    }
}