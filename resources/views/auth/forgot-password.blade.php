@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title text-center">Restablecer contraseña</h1>

                <p class="text-muted text-center">
                    ¿Olvidaste tu contraseña? No te preocupes. Ingresa tu correo y te enviaremos un enlace para restablecerla.
                </p>

                <!-- Mensaje de estado de la sesión -->
                @if (session('status'))
                    <div class="alert alert-success text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Dirección de correo -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Enviar enlace de restablecimiento
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">Volver al inicio de sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
