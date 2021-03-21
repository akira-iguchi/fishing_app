@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="mx-auto d-block col-lg-8" id="calendar"></div>

            @include('events.modal')

            <div class="mx-auto d-block col-lg-4 event_form_body">
                <h4 class="font-weight-bold">釣りを記録しよう</h4>
                <div class="event_form">
                    <form action="" name="eventForm">
                        @csrf

                        <div class="date">
                            <label class="required">日付</label>
                            <input type="date" name="date" required>
                        </div>

                        <div class="date">
                            <label>時間</label>
                            <input type="time" name="fishing_start_time">〜<input type="time" name="fishing_end_time">
                        </div>

                        <div class="form-field">
                            <label class="required">釣り方</label>
                            <input type="text" class="input-text js-input" name="fishing_type" placeholder="例）サビキ釣り" required>
                        </div>

                        <div class="form-field">
                            <label class="required">釣り場</label>
                            <input type="text" class="input-text js-input" name="spot" placeholder="例）かもめ大橋" required>
                        </div>

                        <div class="form-field">
                            <label class="event_label">エサ</label>
                            <input type="text" class="input-text js-input" name="bait" placeholder="例）アミエビ">
                        </div>

                        <div class="form-field">
                            <label class="event_label">天気</label>
                            <input type="text" class="input-text js-input" name="weather" placeholder="例）晴れ">
                        </div>

                        <div class="form-field">
                            <label class="event_label">詳細</label><br>
                            <textarea rows="6" id="textArea" class="form-input-text_area js-input js-text" name="detail" placeholder="例） アジがたくさん釣れた。"></textarea>
                            残り<span id="textLest">100</span>文字
                            <p id="textAttention" style="display:none; color:red;">入力文字数が多すぎます。</p>
                        </div>

                        <div>
                            <button type='button' class="spot-create-edit-button" onclick="addEvent(calendar)"><i class="fas fa-pencil-alt"></i>&thinsp;投稿</button>
                        </div>
                    </div>

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
@endpush