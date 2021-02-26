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
                    @include('user_follow.follow_button')
                </div>
            </div>
        </div>
    @endif
@endsection