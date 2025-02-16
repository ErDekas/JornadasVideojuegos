@extends('admin.dashboard')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Usuario</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Debug info (quitar en producción) -->
    <div class="small text-muted mb-3">
        Ruta del formulario: {{ route('admin.users.update', $user['id']) }}
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user['id']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user['name']) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user['email']) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="registration_type" class="form-label">Tipo de Registro</label>
                    <select name="registration_type" id="registration_type" class="form-control @error('registration_type') is-invalid @enderror" required>
                        <option value="" disabled>Seleccione una opción</option>
                        <option value="virtual" {{ old('registration_type', $user['registration_type']) === 'virtual' ? 'selected' : '' }}>Virtual</option>
                        <option value="presential" {{ old('registration_type', $user['registration_type']) === 'presential' ? 'selected' : '' }}>Presencial</option>
                        <option value="student" {{ old('registration_type', $user['registration_type']) === 'student' ? 'selected' : '' }}>Estudiante</option>
                    </select>
                    @error('registration_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_admin" id="is_admin" class="form-check-input" value="1" {{ old('is_admin', $user['is_admin']) ? 'checked' : '' }}>
                    <label for="is_admin" class="form-check-label">Administrador</label>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para mantener la actual)</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar Cambios
                </button>
            </form>
        </div>
    </div>
</div>
@endsection