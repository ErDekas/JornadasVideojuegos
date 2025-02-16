@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="card-title">{{ $event['event']['title'] }}</h1>
        <p class="card-text">{{ $event['event']['description']}}</p>
        <div class="row mt-4">
            <div class="col-md-6">
                <h2 class="h4">Detalles del evento</h2>
                <ul class="list-unstyled">
                    <li><strong>Fecha:</strong> <span id="formatted-date">{{ $event['event']['date'] }}</span></li>
                    <li><strong>Hora de inicio:</strong> <span id="formatted-start-time">{{ $event['event']['start_time'] }}</span></li>
                    <li><strong>Hora de fin:</strong> <span id="formatted-end-time">{{ $event['event']['end_time'] }}</span></li>
                    <li><strong>Ubicación:</strong> {{ $event['event']['location'] }}</li>
                    <li><strong>Número de asistentes:</strong> {{ $event['event']['current_attendees'] }}</li>
                </ul>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('events.register', $event['event']['id']) }}" class="btn btn-success btn-lg">
                    Registrarse
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para formatear fecha
    function formatDate(isoString) {
        const date = new Date(isoString);
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric'
        };
        return date.toLocaleDateString('es-ES', options);
    }

    // Función para formatear hora
    function formatTime(isoString) {
        const date = new Date(isoString);
        const options = {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        };
        return date.toLocaleTimeString('es-ES', options);
    }

    // Función para limpiar el string ISO que viene de la API
    function cleanISOString(dirtyString) {
        // Elimina la 'Z' del final si existe y cualquier milisegundo
        return dirtyString.replace(/\.\d+Z$/, 'Z').replace('Z', '');
    }

    // Obtener los elementos
    const dateElement = document.getElementById('formatted-date');
    const startTimeElement = document.getElementById('formatted-start-time');
    const endTimeElement = document.getElementById('formatted-end-time');

    // Formatear y actualizar la fecha
    if (dateElement) {
        const rawDate = dateElement.textContent.trim();
        const cleanDate = cleanISOString(rawDate);
        dateElement.textContent = formatDate(cleanDate);
    }

    // Formatear y actualizar la hora de inicio
    if (startTimeElement) {
        const rawStartTime = startTimeElement.textContent.trim();
        const cleanStartTime = cleanISOString(rawStartTime);
        startTimeElement.textContent = formatTime(cleanStartTime);
    }

    // Formatear y actualizar la hora de fin
    if (endTimeElement) {
        const rawEndTime = endTimeElement.textContent.trim();
        const cleanEndTime = cleanISOString(rawEndTime);
        endTimeElement.textContent = formatTime(cleanEndTime);
    }
});
</script>
@endsection