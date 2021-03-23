@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="mx-auto d-block col-lg-8" id="calendar">イベントを掴んで移動も可能！</div>

            <!-- JSでユーザー、イベントのidを取得 -->
            <span id="js-getUserId" data-name="{{ $user->id }}"></span>
            <span id="js-getAuthUserId" data-name="{{ Auth::id() }}"></span>
            <span id="js-getEventId" data-name="{{ $event->id }}"></span>

            @include('events.modal')

            <div class="mx-auto d-block col-lg-4 event_form_body">
                <h4>釣りを記録しよう</h4>
                <div class="event_form">
                    <form method="POST" action="{{ route('events.update', ['event' => $event, 'user' => Auth::user()]) }}" name="eventForm">
                        @csrf
                        @method('PUT')

                        @include('events.form')
                        <div>
                            <button class="spot-create-edit-button"><i class="fas fa-pencil-alt"></i>&thinsp;更新</button>
                        </div>
                    </form>

                </div>
            </div>
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