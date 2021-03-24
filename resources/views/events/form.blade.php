
<div class="date">
    <label class="required">日付</label>
    <input type="date" name="date" value="{{ old('date', $event['date']) }}" required>
</div>
@if($errors->has('date'))
    <span class="error_msg">
        <p id="date_error">{{ $errors->first('date') }}</p>
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
@if($errors->has('fishing_end_time'))
    <span class="error_msg">
        <p>{{ $errors->first('fishing_end_time') }}</p>
    </span>
@endif
<span class="error_msg">
    <p id="time_error"></p>
</span>

<div class="form-field">
    <label class="required">釣り方</label>
    <input type="text" class="input-text" name="fishing_type" placeholder="例）サビキ釣り" value="{{ old('fishing_type', $event['fishing_type']) }}" required>
</div>
@if($errors->has('fishing_type'))
    <span class="error_msg">
        <p>{{ $errors->first('fishing_type') }}</p>
    </span>
@endif
<span class="error_msg">
    <p id="fishing_type_error"></p>
</span>

<div class="form-field">
    <label class="required">釣り場</label>
    <input type="text" class="input-text" name="spot" placeholder="例）かもめ大橋" value="{{ old('spot', $event['spot']) }}" required>
</div>
@if($errors->has('spot'))
    <span class="error_msg">
        <p>{{ $errors->first('spot') }}</p>
    </span>
@endif
<span class="error_msg">
    <p id="spot_error"></p>
</span>

<div class="form-field">
    <label class="event_label">エサ</label>
    <input type="text" class="input-text" name="bait" placeholder="例）アミエビ" value="{{ old('bait', $event['bait']) }}">
</div>
@if($errors->has('bait'))
    <span class="error_msg">
        <p>{{ $errors->first('bait') }}</p>
    </span>
@endif
<span class="error_msg">
    <p id="bait_error"></p>
</span>

<div class="form-field">
    <label class="event_label">天気</label>
    <input type="text" class="input-text" name="weather" placeholder="例）晴れ" value="{{ old('weather', $event['weather']) }}">
</div>
@if($errors->has('weather'))
    <span class="error_msg">
        <p>{{ $errors->first('weather') }}</p>
    </span>
@endif
<span class="error_msg">
    <p id="weather_error"></p>
</span>

<div class="form-field">
    <label class="event_label">詳細</label><br>
    <textarea rows="5" id="textArea" class="form-input-text_area" name="detail" placeholder="例） アジがたくさん釣れた。">{{ old('detail', $event['detail']) }}</textarea>
    残り<span id="textLest">100</span>文字
    <p id="textAttention" style="display:none; color:red;">入力文字数が多すぎます。</p>
</div>
@if($errors->has('detail'))
    <span class="error_msg">
        <p>{{ $errors->first('detail') }}</p>
    </span>
@endif
<span class="error_msg">
    <p id="detail_error"></p>
</span>

<div class="text-center mb-3 error_msg" id="event_success"></div>
<div class="text-center mb-3 error_msg" id="event_error"></div>