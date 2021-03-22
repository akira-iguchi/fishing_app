
<div class="date">
    <label class="required">日付</label>
    <input type="date" name="date" value="{{ old('date', $event['date']) }}">
</div>
@if($errors->has('spot_name'))
        <span class="error_msg">
            <p>{{ $errors->first('spot_name') }}</p>
        </span>
    @endif
<span class="error_msg">
    <p id="date_error"></p>
</span>

<div class="date">
    <label>時間</label>
    <input type="time" name="fishing_start_time" value="{{ old('fishing_start_time', $event['fishing_start_time']) }}">
    〜
    <input type="time" name="fishing_end_time" value="{{ old('fishing_end_time', $event['fishing_end_time']) }}">
</div>

<div class="form-field">
    <label class="required">釣り方</label>
    <input type="text" class="input-text" name="fishing_type" placeholder="例）サビキ釣り" value="{{ old('fishing_type', $event['fishing_type']) }}">
</div>
<span class="error_msg">
    <p id="fishing_type_error"></p>
</span>

<div class="form-field">
    <label class="required">釣り場</label>
    <input type="text" class="input-text" name="spot" placeholder="例）かもめ大橋" value="{{ old('spot', $event['spot']) }}">
</div>
<span class="error_msg">
    <p id="spot_error"></p>
</span>

<div class="form-field">
    <label class="event_label">エサ</label>
    <input type="text" class="input-text" name="bait" placeholder="例）アミエビ" value="{{ old('bait', $event['bait']) }}">
</div>
<span class="error_msg">
    <p id="bait_error"></p>
</span>

<div class="form-field">
    <label class="event_label">天気</label>
    <input type="text" class="input-text" name="weather" placeholder="例）晴れ" value="{{ old('weather', $event['weather']) }}">
</div>
<span class="error_msg">
    <p id="weather_error"></p>
</span>

<div class="form-field">
    <label class="event_label">詳細</label><br>
    <textarea rows="6" id="textArea" class="form-input-text_area" name="detail" placeholder="例） アジがたくさん釣れた。">{{ old('detail', $event['detail']) }}</textarea>
    残り<span id="textLest">100</span>文字
    <p id="textAttention" style="display:none; color:red;">入力文字数が多すぎます。</p>
</div>
<span class="error_msg">
    <p id="detail_error"></p>
</span>

<div class="text-center mb-3 error_msg" id="event_success"></div>
<div class="text-center mb-3 error_msg" id="event_error"></div>