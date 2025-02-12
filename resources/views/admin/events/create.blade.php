@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Crear Nuevo Evento</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.eventos.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                           id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                    @error('titulo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                   id="fecha" name="fecha" value="{{ old('fecha') }}" required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hora" class="form-label">Hora</label>
                            <input type="time" class="form-control @error('hora') is-invalid @enderror" 
                                   id="hora" name="hora" value="{{ old('hora') }}" required>
                            @error('hora')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="ponente_id" class="form-label">Ponente</label>
                    <select class="form-select @error('ponente_id') is-invalid @enderror" 
                            id="ponente_id" name="ponente_id" required>
                        <option value="">Selecciona un ponente</option>
                        @foreach($ponentes as $ponente)
                            <option value="{{ $ponente->id }}" 
                                    {{ old('ponente_id') == $ponente->id ? 'selected' : '' }}>
                                {{ $ponente->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('ponente_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.eventos.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection