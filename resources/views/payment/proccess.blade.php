@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Procesar pago</h1>
                <div class="mb-4">
                    <h2 class="h4">Detalles del evento</h2>
                    <ul class="list-unstyled">
                        <li><strong>Evento:</strong> {{ $registration->event->title }}</li>
                        <li><strong>Fecha:</strong> {{ $registration->event->date }}</li>
                        <li><strong>Precio:</strong> ${{ $registration->event->price }}</li>
                    </ul>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('paypal.pay') }}" class="btn btn-primary btn-lg">
                        Pagar con PayPal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection