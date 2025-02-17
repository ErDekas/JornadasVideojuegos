@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Mi Perfil</h1>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <span class="form-control">{{ $user['users']['name'] }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <span class="form-control">{{ $user['users']['email'] }}</span>
                    </div>
                </form>

                
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h2 class="h4">Mis Eventos</h2>
                @if(!empty($user['eventUser']))
                    <div class="list-group">
                        @foreach($user['eventUser'] as $event)
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
                    <p class="text-muted">Aún no estás registrado en ningún evento.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection