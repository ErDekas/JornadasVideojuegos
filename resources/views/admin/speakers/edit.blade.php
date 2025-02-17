@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Editar Ponente</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.speakers.update', $ponente['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $ponente['name']) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="expertise_areas" class="form-label">Especialidad</label>
                    <input type="text" class="form-control @error('expertise_areas') is-invalid @enderror" 
                           id="expertise_areas" name="expertise_areas" value="{{ old('expertise_areas', is_array($ponente['expertise_areas']) ? implode(', ', $ponente['expertise_areas']) : $ponente['expertise_areas']) }}" required>
                    @error('expertise_areas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="social_links" class="form-label">Redes sociales</label>
                    <textarea class="form-control @error('social_links') is-invalid @enderror" 
                              id="social_links" name="social_links" rows="4">{{ old('social_links', is_array($ponente['social_links']) ? implode(', ', $ponente['social_links']) : $ponente['social_links']) }}</textarea>
                    @error('social_links')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($ponente['photo_url'])
                <div class="mb-3">
                    <label class="form-label">Imagen Actual</label>
                    <div>
                        <img src="{{ $ponente['photo_url'] }}" alt="Foto del ponente" class="img-thumbnail" style="max-width: 200px">
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <label for="photo_url" class="form-label">Nueva Foto</label>
                    <input type="file" class="form-control @error('photo_url') is-invalid @enderror" 
                           id="photo_url" name="photo_url">
                    <small class="form-text text-muted">Dejar en blanco para mantener la imagen actual</small>
                    @error('photo_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.speakers.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Ponente</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
