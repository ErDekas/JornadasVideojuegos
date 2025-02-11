@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-body text-center">
        <img src="{{ $speaker['speaker']['photo_url'] }}" alt="{{ $speaker['speaker']['name'] }}" 
             class="rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover;">
        <h1>{{ $speaker['speaker']['name'] }}</h1>
        <p class="lead">{{ $speaker['speaker']['expertise_areas'] }}</p>

        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <h2 class="h4">Biografía</h2>
                @dd($speaker)
                <p>{{ $speaker['speaker']['biography']}}</p>

                <h2 class="h4 mt-4">Próximos eventos</h2>
                <ul class="list-group">
                    @foreach($speaker['speaker']['upcoming_events'] as $event)
                        <li class="list-group-item">
                            <a href="{{ route('events.show', $event['id']) }}">
                                {{ $event['title'] }}
                            </a>
                            <span class="text-muted"> - {{ $event['date'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection