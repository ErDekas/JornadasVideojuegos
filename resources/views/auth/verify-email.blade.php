@extends('layouts.app')
@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-4 text-center">
            @if($success)
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">
                    ¡Email Verificado!
                </h2>
                <p class="mt-2 text-gray-600">
                    Tu dirección de correo electrónico ha sido verificada correctamente.
                </p>
            @else
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Error de Verificación
                </h2>
                <p class="mt-2 text-gray-600">
                    {{ $message }}
                </p>
            @endif
        </div>

        <div class="flex items-center justify-center mt-4">
            @if($success)
                <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                    Ir a Iniciar Sesión
                </a>
            @else
                <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                    Volver al Inicio
                </a>
            @endif
        </div>
    </div>
</div>
@endsection