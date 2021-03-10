<div class="profile">
    <div class="profile_top">
        <div class="profile_image">
            <img src="{{ asset('storage/'.$user->user_image) }}" alt="ユーザーの画像">
        </div>

        <div class="profile_content">
            <div class="profile_name">{{ $user->user_name }}</div>

            <!-- フォロー／アンフォローボタン -->
            <div class="follow_btn">
                @include('follows.follow_button')
            </div>
        </div>

        @if (Auth::id() == $user->id)
            @unless (Auth::id() == 1)
                <a href="{{ route('users.edit', $user->id)}}" class="btn btn-primary profile_edit_link">プロフィールの編集</a>
            @endunless
        @endif
    </div>

    @if(isset( $user->introduction ))
        <div class="profile_under">
            <p>{!! nl2br(e($user->introduction)) !!}</p>
        </div>
    @endif
</div>