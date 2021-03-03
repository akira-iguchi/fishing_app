@if( Auth::id() !== $user->id )
    <follow-button
        :initial-is-followed-by='@json($user->isFollowedBy(Auth::user()))'
        :initial-count-followings='@json($user->count_followings)'
        :initial-count-followers='@json($user->count_followers)'
        :authorized='@json(Auth::check())'
        endpoint="{{ route('users.follow', ['user' => $user]) }}"
    >
    </follow-button>
@else
    <span>{{ $user->count_followings }} フォロー</span>
    <span>{{ $user->count_followers }} フォロワー</span>
@endif