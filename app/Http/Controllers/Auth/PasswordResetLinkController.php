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
        $this->initializeMailer(); // Initialize PHPMailer properly
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            // Log the request for debugging
            Log::info('Initiating password reset request for email: ' . $request->email);
            // Make the API request
            $apiResponse = $this->apiService->post('/password/forgot', [
                'email' => $request->email
            ]);

            Log::info('API Response:', ['response' => $apiResponse]);

            // Check for API success
            if (isset($apiResponse['success']) && $apiResponse['success'] === true) {
                return back()->with('status', 'Se ha enviado un enlace para restablecer la contraseña a tu correo electrónico.');
            }

            // Handle specific API error messages
            $errorMessage = $apiResponse['message'] ?? 'Hubo un error al procesar la solicitud.';
            Log::error('API Error:', ['message' => $errorMessage]);

            return back()->withErrors([
                'email' => $errorMessage
            ]);

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTrace());
            Log::error('Password reset request failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'email' => 'Hubo un problema con la solicitud. Por favor, intenta de nuevo más tarde.'
            ]);
        }
    }

    /**
     * Handle the reset password response from API
     */
    private function handleResetResponse($response): RedirectResponse
    {
        if ($response['success'] ?? false) {
            return back()->with('status', $response['message'] ?? 'El enlace de recuperación ha sido enviado correctamente.');
        }

        return back()->withErrors(['email' => $response['message'] ?? 'Error al procesar la solicitud de restablecimiento.']);
    }
}
