<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\RequestException;

class Handler extends ExceptionHandler
{
    public function register()
    {
        $this->renderable(function (RequestException $e) {
            if ($e->response->status() === 401) {
                return redirect()->route('login')
                    ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
            }

            return response()->view('errors.api', [
                'message' => 'Error en la comunicación con el servidor.'
            ], 500);
        });
    }
}