@extends('layouts.app')
@section('content')
<h1 class="mb-4">Ponentes</h1>
<div class="row">
    @foreach($speakers['speakers'] as $speaker)
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <img src="{{ $speaker['photo_url'] }}"
                    alt="{{ $speaker['name'] }}"
                    class="rounded-circle mb-3"
                    style="width: 150px; height: 150px; object-fit: cover;">
                <h5 class="card-title">{{ $speaker['name'] }}</h5>
                <ul class="list-unstyled">
                    @php
                        $expertise = is_string($speaker['expertise_areas']) ? json_decode($speaker['expertise_areas'], true) : $speaker['expertise_areas'];
                    @endphp

                    @if(is_array($expertise))
                        @foreach($expertise as $area)
                            <li>{{ $area }}</li>
                        @endforeach
                    @endif
                </ul>
                <a href="{{ route('speakers.show', $speaker['id']) }}" class="btn btn-primary">
                    Ver perfil
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
