@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="card-title">{{ $event->title }}</h1>
        <p class="card-text">{{ $event->description }}</p>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <h2 class="h4">Detalles del evento</h2>
                <ul class="list-unstyled">
                    <li><strong>Fecha:</strong> {{ $event->date }}</li>
                    <li><strong>Ubicación:</strong> {{ $event->location }}</li>
                    <li><strong>Precio:</strong> ${{ $event->price }}</li>
                </ul>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('events.register', $event->id) }}" class="btn btn-success btn-lg">
                    Registrarse
                </a>
            </div>
        </div>
    </div>
</div>
@endsection