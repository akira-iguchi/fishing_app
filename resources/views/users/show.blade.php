@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row user-container">
                @include('users.profile')

                <user-tabs
                    :initial-count-user-spots='@json($user->count_spots)'
                    :initial-count-user-favorite-spots='@json($user->count_favorite_spots)'
                    :initial-count-spot-comments='@json($user->count_spot_comments)'
                    user-id="{{ $user->id }}"
                >
                </user-tabs>
            </div>
        </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('/js/seeMore.js') }}" defer></script>
@endpush