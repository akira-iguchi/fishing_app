<input id="spot_latitude" type="hidden" name="latitude" value="35.6594666">
<input id="spot_longitude" type="hidden" name="longitude" value="139.7005536">

<div class="form-group">
    <label for="spot_name" class="required">釣りスポット名</label>
    <input id="spot_name" type="text" class="form-control" name="spot_name" value="{{ old('spot_name', $spot['spot_name']) }}">

    @if($errors->has('spot_name'))
        <span class="error_msg">
            <p>{{ $errors->first('spot_name') }}</p>
        </span>
    @endif
</div>

<div class="form-group">
    <label for="spot_address">所在地</label>
    <input id="spot_address" type="text" class="form-control" name="address" value="{{ old('address', $spot['address']) }}">

    @if($errors->has('address'))
    <span class="error_msg">
        <p>{{ $errors->first('address') }}</p>
    </span>
    @endif
</div>

<div class="form-group">
    <label for="tags">タグ</label>
    <spot-tags-input
        :initial-tags='@json($tagNames ?? [])'
        :autocomplete-items='@json($allTagNames ?? [])'
    >
    </spot-tags-input>
    @if($errors->has('tags'))
    <span class="error_msg">
        <p>{{ $errors->first('tags') }}</p>
    </span>
    @endif
</div>

<div class="form-group">
    <label for="spot_image">画像</label>
    <input id="spot_image" type="file" name="spot_image">
    @if($errors->has('spot_image'))
        <span class="error_msg">
            <p>{{ $errors->first('spot_image') }}</p>
        </span>
    @endif
</div>

<div class="form-group">
    <label for="textArea" class="required">説明</label>
    <textarea rows="6" id="textArea" class="form-control" name="explanation">{{ old('explanation', $spot['explanation']) }}</textarea>
    残り<span id="textLest">300</span>文字
    <p id="textAttention" style="display:none; color:red;">入力文字数が多すぎます。</p>

    @if($errors->has('explanation'))
        <span class="error_msg">
            <p>{{ $errors->first('explanation') }}</p>
        </span>
    @endif
</div>