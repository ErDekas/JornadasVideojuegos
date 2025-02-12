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
                        <input type="text" name="name" value="{{ $user['users']['name'] }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ $user['users']['email'] }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar perfil</button>
                </form>

                
            </div>
        </div>
    </div>
</div>
@endsection