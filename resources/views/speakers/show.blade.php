@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-body text-center">
        <img src="http://127.0.0.1:8080/storage/{{ $speaker['speaker']['photo_url'] }}" alt="{{ $speaker['speaker']['name'] }}" 
             class="rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover;">
        <h1>{{ $speaker['speaker']['name'] }}</h1>
            <ul class="list-unstyled">
                @php
                    $expertise = json_decode($speaker['speaker']['expertise_areas'], true);
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
                    $socialLinks = json_decode($speaker['speaker']['social_links'], true);
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
    </div>
</div>
@endsection