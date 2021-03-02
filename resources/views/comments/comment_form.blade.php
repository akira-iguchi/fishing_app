<form method="POST" action="{{ route('comments.store', $spot->id) }}" enctype="multipart/form-data">
    @csrf
    @method('POST')

    <div class="form-group">
        <textarea rows="4" id="textAreaComment" class="form-control mt-4" name="comment" placeholder="コメントしよう！"></textarea>
        残り<span id="textLestComment">150</span>文字
        <p id="textAttentionComment" style="display:none; color:red;">入力文字数が多すぎます。</p>

        @if($errors->has('comment'))
            <span class="error_msg">
                <p>{{ $errors->first('comment') }}</p>
            </span>
        @endif
    </div>

    <div class="form-group">
        <label>画像</label>
        <input type="file" name="comment_image">
    </div>

    <button class="spot-create-edit-button"><i class="fas fa-pencil-alt"></i>&thinsp;コメント</button>

</form>