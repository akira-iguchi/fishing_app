<template>
    <div>

        <div class="form-group">
            <label for="spot_name" class="required">釣りスポット名</label>
            <input id="spot_name" type="text" class="form-control" name="spot_name" placeholder="例） 〇〇釣り公園" required>
        </div>

        <div class="form-group">
            <label for="spot_address">所在地</label>
            <input id="spot_address" type="text" class="form-control" name="address" placeholder="例） 〇〇県〇〇市〇〇区〇〇町1-1-1">
        </div>

        <!-- <div class="form-group">
            <label for="tags">タグ（５つまで）</label>
            <spot-tags-input
                :initial-tags='@json($tagNames ?? [])'
                :autocomplete-items='@json($allTagNames ?? [])'
            >
            </spot-tags-input
        </div> -->

        <!-- <div class="form-group">
            <label>おすすめの釣り方</label><br>

            <div class="fishing_type_form">
                @foreach ($allFishingTypeNames as $fishingType)
                    <label class="mr-2" for="{{ $fishingType->id }}">
                        <input id="{{ $fishingType->id }}" type="checkbox" name="fishing_types[]" value="{{ $fishingType->id }}"
                            {{ $spot->fishingTypes->contains('id', $fishingType->id) ? 'checked="checked"' : '' }}
                        > {{ $fishingType->fishing_type_name }}
                    </label>
                @endforeach
            </div>
        </div> -->

        <div class="form-group">
            <label>画像（３つまで）</label><br>
            <input id="image1" type="file" name="spot_image1">
            <p class="text-danger" id="file1_hidden">画像ファイルを選択してください</p>
            <p><img id="file1-preview"></p>
        </div>

        <div class="form-group" id="image2_hidden">
            <input id="image2" type="file" name="spot_image2">
            <p class="text-danger" id="file2_hidden">画像ファイルを選択してください</p>
            <p><img id="file2-preview"></p>
        </div>

        <div class="form-group" id="image3_hidden">
            <input id="image3" type="file" name="spot_image3">
            <p class="text-danger" id="file3_hidden">画像ファイルを選択してください</p>
            <p><img id="file3-preview"></p>
        </div>

        <div class="form-group">
            <label for="textAreaExplanation" class="required">説明</label>
            <textarea rows="6" id="textAreaExplanation" class="form-control" name="explanation" placeholder="例） 風が弱くて釣りやすい釣り場です。" required></textarea>
            残り<span id="textLestExplanation">300</span>文字
            <p id="textAttentionExplanation" style="display:none; color:red;">入力文字数が多すぎます。</p>
        </div>
    </div>
</template>

<script>
    export default {
    }
</script>