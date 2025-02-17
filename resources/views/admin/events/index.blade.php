@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Eventos</h2>
        <a href="{{ route('admin.events.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Agregar Nuevo
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Plazas</th>
                            <th>Asistentes</th>
                            <th>Ubicación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $evento)
                        <tr>
                            <td>{{ $evento['id'] }}</td>
                            <td>{{ $evento['title'] }}</td>
                            <td>{{ $evento['description'] }}</td>
                            <td>{{ $evento['type'] }}</td>
                            <td class="text-muted date-to-format" data-date="{{ $evento['date'] }}">{{ $evento['date'] }}</td>
                            <td>{{ $evento['start_time'] }}</td>
                            <td>{{ $evento['end_time'] }}</td>
                            <td>{{ $evento['max_attendees'] }}</td>
                            <td>{{ $evento['current_attendees'] }}</td>
                            <td>{{ $evento['location'] }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.events.edit', $evento['id']) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.events.destroy', $evento['id']) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Estás seguro?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            
        </div>
    </div>
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