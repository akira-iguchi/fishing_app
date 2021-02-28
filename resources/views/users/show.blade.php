@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row user-container">
                <div class="profile">
                    <div class="profile_image">
                        <img src="{{ asset('storage/'.$user->user_image) }}" alt="ユーザーの画像">
                    </div>
                    <div class="profile_content">
                        <div class="profile_name">{{ $user->user_name }}</div>
                    </div>
                    @if (Auth::id() == $user->id)
                        @unless (Auth::id() == 1)
                            <div class="profile_edit_link_button">
                                <a href="{{ route('users.edit', $user->id)}}" class="user_edit_link_button">プロフィールの編集</a>
                            </div>
                        @endunless
                    @endif

                    {{ $user->spots_count }}
                    <!-- フォロー／アンフォローボタン -->
                    @if( Auth::id() !== $user->id )
                        <follow-button
                            class="ml-auto"
                            :initial-is-followed-by='@json($user->isFollowedBy(Auth::user()))'
                            :authorized='@json(Auth::check())'
                            endpoint="{{ route('users.follow', ['user' => $user]) }}"
                        >
                        </follow-button>
                    @endif
                    <p>{{ $user->count_followings }}フォロー</p>
                    <p>{{ $user->count_followers }}フォロワー</p>
                </div>
                .
            </div>
        </div>
    @endif
@endsection