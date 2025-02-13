@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Registro</h1>
                <form action="{{ route('register') }} " method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de inscripción</label>
                        <select name="tipo_inscripcion" class="form-select @error('tipo_inscripcion') is-invalid @enderror" required>
                            <option value="">Selecciona un tipo</option>
                            <option value="presential" {{ old('tipo_inscripcion') == 'presential' ? 'selected' : '' }}>Presencial</option>
                            <option value="virtual" {{ old('tipo_inscripcion') == 'virtual' ? 'selected' : '' }}>Virtual</option>
                            <option value="student" {{ old('tipo_inscripcion') == 'student' ? 'selected' : '' }}>Estudiante</option>
                        </select>
                        @error('tipo_inscripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="certificadoAlumnoContainer" style="display: none;">
                        <div class="form-check">
                            <input type="checkbox" name="certificado_alumno" class="form-check-input @error('certificado_alumno') is-invalid @enderror"
                                id="certificadoAlumno" value="1" {{ old('certificado_alumno') ? 'checked' : '' }}>
                            <label class="form-check-label" for="certificadoAlumno">
                                Certifico que soy alumno
                            </label>
                            @error('certificado_alumno')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoInscripcion = document.querySelector('select[name="tipo_inscripcion"]');
        const certificadoContainer = document.querySelector('#certificadoAlumnoContainer');

        function toggleCertificadoAlumno() {
            if (tipoInscripcion.value === 'student') { // Cambiado de 'gratuita' a 'free'
                certificadoContainer.style.display = 'block';
            } else {
                certificadoContainer.style.display = 'none';
            }
        }

        tipoInscripcion.addEventListener('change', toggleCertificadoAlumno);
        toggleCertificadoAlumno(); // Run on initial load
    });
</script>
@endpush
@endsection