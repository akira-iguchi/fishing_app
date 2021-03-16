@extends('layouts.app')

@section('content')
    <div class="container">
        @include('spots.searches.search_form')

        <h2 class="search-result"><span>{{ $tag->hashtag }}</span>の検索結果</h2>

        <p class="search_count">{{ $tag->spots->count() }}件</p>

        <div class="row">
            @include('spots.cards.card', ['spots' => $tag->spots])
        </div>

        @include('spots.seeMore', ['spots' => $tag->spots])
    </div>
@endsection

@push('js')
    <script src="{{ asset('/js/seeMore.js') }}" defer></script>
@endpush