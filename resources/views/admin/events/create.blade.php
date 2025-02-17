@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Crear Nuevo Evento</h3>
        </div>
        <div class="card-body">

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


            <form action="{{ route('admin.events.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Título</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                        id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                        id="description" name="description" required>{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Tipo</label>
                    <select class="form-select @error('type') is-invalid @enderror"
                        id="type" name="type" required>
                        <option value="">Selecciona un tipo</option>
                        <option value="conference" {{ 'type' == 'conference' ? 'selected' : '' }}>Conferencia</option>
                        <option value="workshop" {{ 'type' == 'workshop' ? 'selected' : '' }}>Workshop</option>
                    </select>
                    @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="date" class="form-label">Fecha</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                id="date" name="date" value="{{ old('date') }}" required>
                            @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Hora de inicio</label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="end_time" class="form-label">Hora de fin</label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="max_attendees" class="form-label">Máximo de asistentes</label>
                            <input type="number" class="form-control @error('max_attendees') is-invalid @enderror"
                                id="max_attendees" name="max_attendees" value="{{ old('max_attendees') }}" required>
                            @error('max_attendees')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="current_attendees" class="form-label">Asistentes actuales</label>
                            <input type="number" class="form-control @error('current_attendees') is-invalid @enderror"
                                id="current_attendees" name="current_attendees" value="{{ old('current_attendees', 'current_attendees') }}" required>
                            @error('current_attendees')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="location" class="form-label">Ubicación</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                id="location" name="location" value="{{ old('location') }}" required>
                            @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="speakers" class="form-label">Ponentes</label>
                    <select class="form-select @error('speakers') is-invalid @enderror"
                        id="speakers" name="speakers[]" multiple required>
                        @foreach($ponentes as $ponente)
                        <option value="{{ $ponente['id'] }}"
                            {{ in_array($ponente['id'], old('speakers', [])) ? 'selected' : '' }}>
                            {{ $ponente['name'] ?? $ponente['nombre'] }}
                        </option>
                        @endforeach
                    </select>
                    @error('speakers')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection