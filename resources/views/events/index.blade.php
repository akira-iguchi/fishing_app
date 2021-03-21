@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="mx-auto d-block col-lg-8" id="calendar">イベントを掴んで移動も可能！</div>

            <!-- JSでユーザーのidを取得 -->
            <span id="js-getUserId" data-name="{{ $user->id }}"></span>

            @include('events.modal')

            @include('events.form')
        </div>
    </div>
@endsection

@push('js')
    <script src="/js/ajax-setup.js" defer></script>
    <script src='/js/fullcalendar/core/main.js' defer></script>
    <script src='/js/fullcalendar/daygrid/main.js' defer></script>
    <script src='/js/fullcalendar/interaction/main.js' defer></script>
    <script src='/js/fullcalendar.js' defer></script>
    <script src='/js/event-control.js' defer></script>
    <script src="{{ asset('/js/wordCount.js') }}" defer></script>
@endpush