<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login')
                ->with('error', 'Por favor inicia sesión para continuar.');
        }

        return $next($request);
    }
}