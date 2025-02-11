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
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar perfil</button>
                </form>

                <h2 class="h4 mt-4">Mis registros</h2>
                <div class="list-group mt-3">
                    @foreach($user->registrations as $registration)
                        <div class="list-group-item">
                            <h5 class="mb-1">{{ $registration->event->title }}</h5>
                            <p class="mb-1">{{ $registration->event->date }}</p>
                            <form action="{{ route('events.registration.cancel', $registration->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Cancelar registro
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection