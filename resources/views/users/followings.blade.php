@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row user-container">
                @include('users.profile')

                <div class="user-tabs">
                    <ul class="nav nav-tabs nav-justified mt-3">
                        <li class="nav-item">
                            <a class="nav-link text-muted"
                            href="{{ route('users.show', ['user' => $user]) }}">
                            釣りスポット <span class="badge badge-secondary">{{ $user->count_spots }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-muted"
                            href="">
                            いいね <span class="badge badge-secondary">{{ $user->count_favorite_spots }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-muted active"
                            href="{{ route('users.followings', ['user' => $user]) }}">
                            フォロー <span class="badge badge-secondary">{{ $user->count_followings }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-muted"
                            href="">
                            フォロワー <span class="badge badge-secondary">{{ $user->count_followers }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="row tab_under">
                    @foreach ($followings as $user)
                    <div class="mx-auto d-block col-lg-4 col-md-6">
                        <div class="mt-4 mb-5 text-center">
                            <div class="profile_image">
                                <img src="{{ asset('storage/'.$user->user_image) }}" alt="ユーザーの画像">
                            </div>

                            <div class="profile_content">
                                <a href="{{ route('users.show', ['user' => $user]) }}">
                                    <p class="followings_user_name"><strong>{{ $user->user_name }}</strong></p>
                                </a>

                                <!-- フォロー／アンフォローボタン -->
                                <div>
                                    @include('follows.follow_button')
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection