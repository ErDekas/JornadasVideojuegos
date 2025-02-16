@extends('layouts.app')
@section('content')
<h1 class="mb-4">Eventos</h1>
<div class="row">
    @foreach($events['events'] as $event)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $event['title'] }}</h5>
                    <p class="card-text">{{ $event['description'] }}</p>
                    <p class="text-muted date-to-format" data-date="{{ $event['date'] }}">{{ $event['date'] }}</p>
                    <a href="{{ route('events.show', $event['id']) }}" class="btn btn-primary">Ver más</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los elementos con fechas
    const dateElements = document.querySelectorAll('.date-to-format');

    // Función para formatear la fecha
    function formatDate(isoString) {
        const date = new Date(isoString);
        
        const options = { 
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        return date.toLocaleDateString('es-ES', options);
    }

    // Función para limpiar el string ISO
    function cleanISOString(dirtyString) {
        return dirtyString.replace(/\.\d+Z$/, 'Z').replace('Z', '');
    }

    // Formatear cada fecha
    dateElements.forEach(element => {
        const rawDate = element.dataset.date;
        if (rawDate) {
            const cleanDate = cleanISOString(rawDate);
            try {
                element.textContent = formatDate(cleanDate);
            } catch (error) {
                console.error('Error al formatear la fecha:', error);
            }
        }
    });
});
</script>
@endsection