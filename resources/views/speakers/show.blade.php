@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-body text-center">
        <img src="{{ $speaker['speaker']['photo_url'] }}" alt="{{ $speaker['speaker']['name'] }}" 
             class="rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover;">
        <h1>{{ $speaker['speaker']['name'] }}</h1>
            <ul class="list-unstyled">
                @php
                    // Verificamos si 'expertise_areas' es una cadena JSON y la decodificamos
                    $expertise = is_string($speaker['speaker']['expertise_areas']) ? json_decode($speaker['speaker']['expertise_areas'], true) : $speaker['speaker']['expertise_areas'];
                @endphp

                @if(is_array($expertise))
                    @foreach($expertise as $area)
                        <li>{{ $area }}</li>
                    @endforeach
                @endif
            </ul>
        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <h2 class="h4 mt-4">Redes Sociales</h2>
                @php
                    // Verificamos si 'social_links' es una cadena JSON y la decodificamos
                    $socialLinks = is_string($speaker['speaker']['social_links']) ? json_decode($speaker['speaker']['social_links'], true) : $speaker['speaker']['social_links'];
                @endphp

                @if(is_array($socialLinks))
                    <ul class="list-unstyled">
                        @foreach($socialLinks as $name => $url)
                            <li>
                                <a href="{{ $url }}" target="_blank">
                                    {{ ucfirst($name) }} 
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-10 mx-auto">
                <h2 class="h4">Eventos donde participa</h2>
                @if(!empty($speaker['eventSpeaker']))
                    <div class="list-group">
                        @foreach($speaker['eventSpeaker'] as $event)
                            <div class="list-group-item">
                                <h3 class="h5">{{ $event['title'] }}</h3>
                                <p class="mb-1">{{ $event['description'] }}</p>
                                <p class="text-muted">
                                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }} <br>
                                    <strong>Hora:</strong> {{ $event['start_time'] }} - {{ $event['end_time'] }} <br>
                                    <strong>Ubicación:</strong> {{ ucfirst($event['location']) }} <br>
                                    <strong>Tipo:</strong> {{ ucfirst($event['type']) }} <br>
                                    <strong>Cupo:</strong> {{ $event['current_attendees'] }}/{{ $event['max_attendees'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Este speaker aún no participa en ningún evento.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
