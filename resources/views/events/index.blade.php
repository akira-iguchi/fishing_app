@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="mx-auto d-block col-lg-8" id="calendar">イベントを掴んで移動も可能！</div>

            <!-- JSでユーザーのidを取得 -->
            <span id="js-getUserId" data-name="{{ $user->id }}"></span>
            <span id="js-getAuthUserId" data-name="{{ Auth::id() }}"></span>

            @include('events.modal')

            @if($user->id === Auth::id())
                <div class="mx-auto d-block col-lg-4 event_form_body">
                    <h4>釣りを記録しよう</h4>
                    <div class="event_form">
                        <form action="" name="eventForm">
                            @csrf
                            @include('events.form')
                            <div>
                                <button type='button' class="spot-create-edit-button" onclick="addEvent()"><i class="fas fa-pencil-alt"></i>&thinsp;投稿</button>
                            </div>
                        </form>

                    </div>
                </div>
            @endif
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