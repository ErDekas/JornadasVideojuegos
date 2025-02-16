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
        <div class="card-body">eventos
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Ponente</th>
                            <th>Asistentes</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $evento)
                        <tr>
                            <td>{{ $evento['id'] }}</td>
                            <td>{{ $evento['title'] }}</td>
                            <td>{{ $evento['description'] }}</td>
                            <td>{{ $evento['type'] }}</td>
                            <td>{{ $evento['date'] }}</td>
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
@endsection