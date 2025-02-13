@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Registro para {{ $event['event']['title'] }}</h1>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @//if()
                    <div class="alert alert-success">
                        Ya estás registrado en este evento.
                    </div>
                @//endif
            </div>
        </div>
    </div>
</div>
@endsection
