<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ApiService;
use Illuminate\Support\Facades\Log;

class NewPasswordController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.password.reset', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        try {
            $apiResponse = $this->apiService->post('/password/reset', [
                'token' => $request->token,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

            if (isset($apiResponse['success']) && $apiResponse['success'] === true) {
                return redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida exitosamente.');
            }

            return back()->withErrors([
                'email' => $apiResponse['message'] ?? 'Error al restablecer la contraseña.'
            ]);

        } catch (\Exception $e) {
            Log::error('Password reset failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'email' => 'Hubo un problema al restablecer la contraseña. Por favor, intenta nuevamente.'
            ]);
        }
    }
}