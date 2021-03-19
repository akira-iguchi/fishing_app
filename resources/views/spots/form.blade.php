<input id="spot_latitude" type="hidden" name="latitude" value="35.6594666">
<input id="spot_longitude" type="hidden" name="longitude" value="139.7005536">

<div class="form-group">
    <label for="spot_name" class="required">釣りスポット名</label>
    <input id="spot_name" type="text" class="form-control" name="spot_name" value="{{ old('spot_name', $spot['spot_name']) }}" placeholder="例） 〇〇釣り公園">

    @if($errors->has('spot_name'))
        <span class="error_msg">
            <p>{{ $errors->first('spot_name') }}</p>
        </span>
    @endif
</div>

<div class="form-group">
    <label for="spot_address">所在地</label>
    <input id="spot_address" type="text" class="form-control" name="address" value="{{ old('address', $spot['address']) }}" placeholder="例） 〇〇県〇〇市〇〇区〇〇町1-1-1">

    @if($errors->has('address'))
    <span class="error_msg">
        <p>{{ $errors->first('address') }}</p>
    </span>
    @endif
</div>

<div class="form-group">
    <label for="tags">タグ（５つまで）</label>
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
    <label>おすすめの釣り方</label><br>

    <div class="fishing_type_form">
        @foreach ($allFishingTypeNames as $fishingType)
            <label class="mr-2" for="{{ $fishingType->id }}">
                <input id="{{ $fishingType->id }}" type="checkbox" name="fishing_types[]" value="{{ $fishingType->id }}"
                    {{ $spot->fishing_types->contains('id', $fishingType->id) ? 'checked="checked"' : '' }}
                > {{ $fishingType->fishing_type_name }}
            </label>
        @endforeach
    </div>

    @if($errors->has('fishing_types'))
    <span class="error_msg">
        <p>{{ $errors->first('fishing_types') }}</p>
    </span>
    @endif
</div>

<div class="form-group">
    <label>画像（３つまで）</label><br>
    <input id="image1" type="file" name="spot_image1">
    <p class="text-danger" id="file1_hidden">画像ファイルを選択してください</p>
    <p><img id="file1-preview"></p>

    @if($errors->has('spot_image1'))
        <span class="error_msg">
            <p>{{ $errors->first('spot_image1') }}</p>
        </span>
    @endif
</div>

<div class="form-group" id="image2_hidden">
    <input id="image2" type="file" name="spot_image2">
    <p class="text-danger" id="file2_hidden">画像ファイルを選択してください</p>
    <p><img id="file2-preview"></p>

    @if($errors->has('spot_image2'))
        <span class="error_msg">
            <p>{{ $errors->first('spot_image2') }}</p>
        </span>
    @endif
</div>

<div class="form-group" id="image3_hidden">
    <input id="image3" type="file" name="spot_image3">
    <p class="text-danger" id="file3_hidden">画像ファイルを選択してください</p>
    <p><img id="file3-preview"></p>

    @if($errors->has('spot_image3'))
        <span class="error_msg">
            <p>{{ $errors->first('spot_image3') }}</p>
        </span>
    @endif
</div>

<div class="form-group">
    <label for="textArea" class="required">説明</label>
    <textarea rows="6" id="textArea" class="form-control" name="explanation" placeholder="例） 風が弱くて釣りやすい釣り場です。">{{ old('explanation', $spot['explanation']) }}</textarea>
    残り<span id="textLest">300</span>文字
    <p id="textAttention" style="display:none; color:red;">入力文字数が多すぎます。</p>

    @if($errors->has('explanation'))
        <span class="error_msg">
            <p>{{ $errors->first('explanation') }}</p>
        </span>
    @endif
</div>