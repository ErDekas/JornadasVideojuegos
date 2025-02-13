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

class RegisterController extends Controller
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
     * @param RegisterUserRequest $request
     * @return RedirectResponse
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            if ($validatedData['tipo_inscripcion'] === 'student' && 
                !str_ends_with($validatedData['email'], '@ayala.com')) {
                    return back()
                        ->withErrors(['email' => 'El correo no corresponde con un correo valido de un estudiante'])
                        ->withInput();
            }

            // Enviar a la API
            $apiResponse = $this->apiService->post('/register', [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'password_confirmation' => $validatedData['password_confirmation'],
                'registration_type' => $validatedData['tipo_inscripcion'],
                'is_verified' => $validatedData['certificado_alumno'] ?? false,
            ]);

            if (!isset($apiResponse['token'])) {
                throw new \Exception('No se recibió token de autenticación de la API.');
            }

            Session::put('api_token', $apiResponse['token']);

            return redirect()->route('home')->with('success', 'Registro exitoso.');

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