<input id="spot_latitude" type="hidden" name="latitude" value="35.6594666">
<input id="spot_longitude" type="hidden" name="longitude" value="139.7005536">

<div class="form-group">
    <label class="required">釣りスポット名</label>
    <input id="spot_name" type="text" class="form-control" name="spot_name" value="{{ old('spot_name', $spot['spot_name']) }}">

    @if($errors->has('spot_name'))
        <span class="error_msg">
            <p>{{ $errors->first('spot_name') }}</p>
        </span>
    @endif
</div>

<div class="form-group">
    <label>所在地</label>
    <input type="text" class="form-control" name="address" value="{{ old('address', $spot['name']) }}">

    @if($errors->has('address'))
    <span class="error_msg">
        <p>{{ $errors->first('address') }}</p>
    </span>
    @endif
</div>

<div class="form-group">
    <spot-tags-input
    >
    </spot-tags-input>
</div>

<div class="form-group">
    <label>画像</label>
    <input type="file" name="spot_image">
</div>

<div class="form-group">
    <label class="required">説明</label>
    <textarea rows="6" id="textArea" class="form-control" name="explanation">{{ old('explanation', $spot['explanation']) }}</textarea>
    残り<span id="textLest">300</span>文字
    <p id="textAttention" style="display:none; color:red;">入力文字数が多すぎます。</p>

    @if($errors->has('explanation'))
        <span class="error_msg">
            <p>{{ $errors->first('explanation') }}</p>
        </span>
    @endif
</div>