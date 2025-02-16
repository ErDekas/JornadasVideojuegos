@extends('admin.dashboard')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Confirmar Eliminación</h3>
        </div>
        <div class="card-body">
            <p>¿Estás seguro de que deseas eliminar al usuario "{{ $user['name'] }}"?</p>

            @if($user['is_admin'])
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Este usuario tiene permisos de administrador. Asegúrate de que esta acción es necesaria.
            </div>
            @endif

            <div class="mt-4">
                <form action="{{ route('admin.users.delete', $user['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-danger">Eliminar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
