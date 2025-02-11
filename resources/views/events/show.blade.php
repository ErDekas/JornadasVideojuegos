@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="card-title">{{ $event['event']['title'] }}</h1>
        <p class="card-text">{{ $event['event']['description']}}</p>
        <div class="row mt-4">
            <div class="col-md-6">
                <h2 class="h4">Detalles del evento</h2>
                <ul class="list-unstyled">
                    <li><strong>Fecha:</strong> {{ $event['event']['date'] }}</li>
                    <li><strong>Ubicación:</strong> {{ $event['event']['location'] }}</li>
                    <li><strong>Número de asistentes:</strong> {{ $event['event']['current_attendees'] }}</li>
                </ul>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('events.register', $event['event']['id']) }}" class="btn btn-success btn-lg">
                    Registrarse
                </a>
            </div>
        </div>
    </div>
</div>
@endsection