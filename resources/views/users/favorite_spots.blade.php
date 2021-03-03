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
                            <a class="nav-link text-muted active"
                            href="{{ route('users.favorites', ['user' => $user]) }}">
                            いいね <span class="badge badge-secondary">{{ $user->favoriteSpots->count() }}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="row">
                        @include('spots.card')
                    </div>
                </div>
                @include('spots.count')
            </div>
        </div>
    @endif
@endsection