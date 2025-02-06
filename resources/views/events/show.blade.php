@extends('layouts.app')

@section('content')
<div class="container">
    <div id="event-detail-root"></div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/app.js') }}"></script>
@endpush
