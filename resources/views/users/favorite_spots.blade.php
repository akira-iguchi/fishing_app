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
                <div class="user-tabs">
                    <ul class="nav nav-tabs nav-justified mt-3">
                        <li class="nav-item">
                            <a class="nav-link text-muted"
                            href="{{ route('users.show', ['user' => $user]) }}">
                            釣りスポット
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-muted active"
                            href="{{ route('users.favorites', ['user' => $user]) }}">
                            いいね
                            </a>
                        </li>
                    </ul>

                    @include('spots.card')
                </div>
                <div>
                    釣り場数{{ $user->count_spots }}
                </div>
            </div>
        </div>
    @endif
@endsection