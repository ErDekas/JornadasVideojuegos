<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function verify(Request $request, $token)
    {
        try {
            // Obtener el email del query string
            $email = $request->query('email');

            if (!$email) {
                throw new \Exception('El email es requerido para la verificación');
            }

            // Llamar a la API para verificar el email
            $response = $this->apiService->post('/verify-email', [
                'email' => $email
            ]);

            return view('auth.verify-email', [
                'success' => isset($response['user']),
                'message' => $response['message'] ?? 'Error al verificar el correo electrónico'
            ]);

        } catch (\Exception $e) {
            Log::error('Error en verificación de email', [
                'error' => $e->getMessage(),
                'token' => $token,
                'email' => $email ?? null
            ]);

            return view('auth.verify-email', [
                'success' => false,
                'message' => 'Hubo un problema al verificar tu correo electrónico. Por favor, intenta nuevamente o contacta a soporte.'
            ]);
        }
    }
}