@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                </div>
                
                <h1 class="card-title h4">¡Registro Exitoso!</h1>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card-text mb-4">

                    <p>Te has registrado correctamente al evento:</p>
                    <h2 class="h5">{{ $registration['event']['title'] }}</h2>
                    <p class="mb-0"><strong>Fecha:</strong> <span id="formatted-date">{{ $registration['event']['date'] }}</span></p>
                    <p><strong>Hora:</strong> <span id="formatted-time">{{ $registration['event']['start_time'] }}</span></p>
                    <p class="mb-0"><strong>Ubicación:</strong> {{ $registration['event']['location'] }}</p>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('events.index') }}" class="btn btn-primary">Volver a Eventos</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function formatDate(isoString) {
        const date = new Date(isoString);
        return date.toLocaleDateString('es-ES', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric'
        });
    }

    function formatTime(isoString) {
        const date = new Date(isoString);
        return date.toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    const dateElement = document.getElementById('formatted-date');
    const timeElement = document.getElementById('formatted-time');

    if (dateElement) {
        dateElement.textContent = formatDate(dateElement.textContent.trim());
    }
    if (timeElement) {
        timeElement.textContent = formatTime(timeElement.textContent.trim());
    }
});
</script>
@endsection