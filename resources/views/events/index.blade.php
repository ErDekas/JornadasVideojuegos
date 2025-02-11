@extends('layouts.app')
@section('content')
<h1 class="mb-4">Eventos</h1>
<div class="row">
    
    @foreach($events['events'] as $event)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $event['title'] }}</h5>
                    <p class="card-text">{{ $event['description'] }}</p>
                    <p class="text-muted">{{ $event['date'] }}</p>
                    <a href="{{ route('events.show', $event['id']) }}" class="btn btn-primary">Ver más</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection