@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Confirmar Eliminación</h3>
        </div>
        <div class="card-body">
            <p>¿Estás seguro de que deseas eliminar el evento "{{ $event['title'] }}"?</p>
            <p>Esta acción no se puede deshacer.</p>

            <div class="mt-4">
                <form action="{{ route('admin.events.destroy', $event['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-danger">Eliminar Evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection