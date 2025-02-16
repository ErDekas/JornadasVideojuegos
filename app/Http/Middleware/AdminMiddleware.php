<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Session::get('user');
        
        if (!$user || !isset($user['is_admin']) || $user['is_admin'] !== true) {
            return redirect()->route('home')->with('error', 'Acceso no autorizado.');
        }

        return $next($request);
    }
}