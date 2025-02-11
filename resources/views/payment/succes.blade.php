@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" 
                         class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                    </svg>
                </div>
                <h1 class="card-title">¡Pago exitoso!</h1>
                <p class="card-text">
                    Tu pago ha sido procesado correctamente. Hemos enviado un correo de confirmación 
                    con los detalles de tu registro.
                </p>
                <a href="{{ route('events.index') }}" class="btn btn-primary">
                    Ver más eventos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection