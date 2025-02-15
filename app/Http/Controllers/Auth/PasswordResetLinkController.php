<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Services\ApiService;
use Illuminate\View\View;
use App\Models\User;
use App\Trait\Mail;
use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    use Mail;
    protected $apiService;

    public function __construct(ApiService $apiService) 
    {
        $this->apiService = $apiService;
        $this->initializeMailer();
    }

    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            Log::info('Initiating password reset request for email: ' . $request->email);
            
            // Hacer la solicitud a la API
            $apiResponse = $this->apiService->post('/password/forgot', [
                'email' => $request->email
            ]);
            
            Log::info('API Response:', ['response' => $apiResponse]);

            if (isset($apiResponse['success']) && $apiResponse['success'] === true) {
                $resetToken = $apiResponse['token'] ?? null;
                $userName = $apiResponse['user']['name'] ?? 'Usuario'; // Obtener el nombre del usuario si está disponible
                
                if (!$resetToken) {
                    throw new \Exception('No se recibió el token de restablecimiento.');
                }

                // Enviar el correo de restablecimiento usando tu método existente
                $emailSent = $this->sendPasswordResetEmail(
                    $request->email,
                    $userName,
                    $resetToken
                );

                if (!$emailSent) {
                    Log::error('Failed to send password reset email', [
                        'email' => $request->email
                    ]);
                    throw new \Exception('Error al enviar el correo de restablecimiento.');
                }

                return back()->with('status', 'Se ha enviado un enlace para restablecer la contraseña a tu correo electrónico.');
            }

            $errorMessage = $apiResponse['message'] ?? 'Hubo un error al procesar la solicitud.';
            Log::error('API Error:', ['message' => $errorMessage]);

            return back()->withErrors([
                'email' => $errorMessage
            ]);

        } catch (\Exception $e) {
            Log::error('Password reset request failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'email' => 'Hubo un problema con la solicitud. Por favor, intenta de nuevo más tarde.'
            ]);
        }
    }
}