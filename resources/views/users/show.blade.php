@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row user-container">
                @include('users.profile')

                <!-- ユーザーのタブ一覧（フォロー、お気に入りボタン含む） -->
                <user-tabs
                    :initial-count-user-spots='@json($user->count_spots)'
                    :initial-count-user-favorite-spots='@json($user->count_favorite_spots)'
                    :initial-count-user-followings='@json($user->count_followings)'
                    :initial-count-user-followers='@json($user->count_followers)'
                    user-id="{{ $user->id }}"
                >
                </user-tabs>
            </div>
        </div>
    @endif
@endsection