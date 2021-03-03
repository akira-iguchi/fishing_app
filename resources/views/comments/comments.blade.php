@foreach ($comments as $comment)
    <div class="comment">
        <div class="mt-2">
            <div class="comment_created_at">{{ $comment->created_at->format('Y/m/d') }}</div>
            <br>
            <a href="{{ route('users.show', $comment->user_id)}}">
                <img src="{{ asset('storage/'.$comment->user->user_image) }}" alt="釣り場投稿者の画像">
                <span class="comment_creater_name">{{ $comment->user->user_name }}</span>
            </a>
        </div>

        <div class="comment_content">
            {!! nl2br(e($comment->comment)) !!}
        </div>

        @if(isset( $comment->comment_image ))
            <div class="comment_img">
                <img src="{{ asset('storage/'.$comment->comment_image) }}" alt="釣り場コメントの画像">
            </div>
        @endif
    </div>

    @if (\Auth::id() === $comment->user_id)
        <div class="comment_delete">
            <form action="{{route('comments.destroy', [$comment->spot_id, $comment->id])}}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('本当に削除しますか？')"><i class="fas fa-trash-alt"></i></button>
            </form>
        </div>
    @endif
@endforeach