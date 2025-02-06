@extends('layouts.app')

@section('content')
<div class="container">
    <div id="event-list-root"></div> {{-- React montará aquí el componente --}}
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/app.js') }}"></script>
@endpush
