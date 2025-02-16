@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Confirmar Eliminación</h3>
        </div>
        <div class="card-body">
            <p>¿Estás seguro de que deseas eliminar al ponente "{{ $ponente['name'] }}"?</p>
            
            @if($ponente['events_count'] > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Este ponente está asignado a {{ $ponente['events_count'] }} eventos. 
                Al eliminarlo, se removerá de todos estos eventos.
            </div>
            @endif

            <div class="mt-4">
                <form action="{{ route('admin.speakers.destroy', $ponente['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.speakers.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-danger">Eliminar Ponente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection