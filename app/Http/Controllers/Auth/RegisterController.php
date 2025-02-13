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

            // Verificar la respuesta de la API
            if (!isset($apiResponse['token'])) {
                Log::error('API response missing token', ['response' => $apiResponse]);
                throw new \Exception('No se recibió token de autenticación de la API.');
            }

            // Guardar el token en la sesión
            Session::put('api_token', $apiResponse['token']);

            try {
                // Enviar correo de confirmación
                $result = $this->sendConfirmationEmail($validatedData['email'], $validatedData['name'], $apiResponse['token']);

                if (!$result) {
                    Log::warning('Error al enviar email de confirmación', [
                        'email' => $validatedData['email']
                    ]);
                }
            } catch (\Exception $emailError) {
                Log::error('Error en envío de email', [
                    'error' => $emailError->getMessage(),
                    'email' => $validatedData['email']
                ]);
                // No lanzamos la excepción aquí para permitir que el registro continúe
            }

            // Agregar un mensaje flash a la sesión
            Session::flash('success', 'Registro exitoso. Por favor, revisa tu correo para confirmar tu cuenta.');

            // Forzar que la sesión se escriba
            Session::save();

            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Error en registro de usuario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $validatedData['email'] ?? null
            ]);

            return back()
                ->withErrors(['error' => 'Hubo un problema al procesar tu registro. Por favor, intenta nuevamente.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
}
