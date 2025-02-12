<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = session('api_token');
        
        if (!$token) {
            return response()->json(['error' => 'No autorizado'], 401);
        }
        // Agregar el token a las cabeceras de todas las peticiones
        $request->headers->set('Authorization', 'Bearer ' . $token);
        
        return $next($request);
    }
}