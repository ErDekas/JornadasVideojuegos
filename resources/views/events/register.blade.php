@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Registro para {{ $event['event']['title'] }}</h1>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card-text mb-4">
                    <p><strong>Fecha:</strong> <span id="formatted-date">{{ $event['event']['date'] }}</span></p>
                    <p><strong>Hora:</strong> <span id="formatted-time">{{ $event['event']['start_time'] }}</span></p>
                    <p><strong>Ubicación:</strong> {{ $event['event']['location'] }}</p>
                    <p><strong>Plazas disponibles:</strong> {{ $availability['available_slots'] }}</p>
                </div>

                @if($availability['available_slots'] > 0)
                    <form action="{{ route('events.register', $event['event']['id']) }}" method="POST">
                        @csrf
                        <div class="alert alert-info">
                            ¿Estás seguro que deseas registrarte en este evento?
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Confirmar Registro</button>
                            <a href="{{ route('events.show', $event['event']['id']) }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">
                        Lo sentimos, no hay plazas disponibles para este evento.
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('events.index') }}" class="btn btn-outline-primary">Volver a Eventos</a>
                    </div>
                @endif
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