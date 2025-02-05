<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $response = $this->apiService->post('/auth/register', $validated);
            
            Session::flash('success', 'Registro exitoso. Por favor, verifica tu correo electrónico.');
            
            return redirect()->route('login');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Ha ocurrido un error al registrar el usuario.'
            ])->withInput($request->except('password'));
        }
    }

    public function verify($token)
    {
        try {
            $response = $this->apiService->get("/auth/verify/{$token}");
            return redirect()->route('login')
                           ->with('success', '¡Email verificado! Ya puedes iniciar sesión.');
        } catch (\Exception $e) {
            return redirect()->route('login')
                           ->with('error', 'El enlace de verificación es inválido.');
        }
    }
}