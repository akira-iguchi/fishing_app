@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row user-container">
                @include('users.profile')

                <div class="user-tabs">
                    <ul class="nav nav-tabs nav-justified mt-3">
                        <li class="nav-item">
                            <a class="nav-link text-muted active"
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
                            <a class="nav-link text-muted"
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
            </div>
        </div>
    @endif
@endsection