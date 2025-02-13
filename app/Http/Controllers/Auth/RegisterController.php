<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\RegisterUserRequest;
use App\Services\ApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Trait\Mail;

class RegisterController extends Controller
{
    use Mail;
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->initializeMailer();
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
     * @param RegisterUserRequest $request
     * @return RedirectResponse
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            // Enviar a la API para registrar al usuario
            $apiResponse = $this->apiService->post('/register', [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'password_confirmation' => $validatedData['password_confirmation'],
                'registration_type' => $validatedData['tipo_inscripcion'],
                'is_verified' => $validatedData['certificado_alumno'] ?? false,
                'role' => 'student'
            ]);

            if (!isset($apiResponse['token'])) {
                throw new \Exception('No se recibió token de autenticación de la API.');
            }

            // Guardar el token en la sesión
            Session::put('api_token', $apiResponse['token']);

            // Enviar correo de confirmación después de registrar al usuario
            $token = $apiResponse['token']; // Si el token de la API es necesario para el enlace de confirmación
            $this->sendConfirmationEmail($validatedData['email'], $validatedData['name'], $token);

            // Redirigir al usuario a la página principal con un mensaje de éxito
            return redirect()->route('home')->with('success', 'Registro exitoso. Por favor, revisa tu correo para confirmar tu cuenta.');

        } catch (\Exception $e) {
            Log::error('Error en registro de usuario', [
                'error' => $e->getMessage(),
                'email' => $validatedData['email'] ?? null
            ]);

            return back()
                ->withErrors(['error' => 'Hubo un problema al procesar tu registro. Por favor, intenta nuevamente.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
}