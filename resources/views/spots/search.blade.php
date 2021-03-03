@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            @include('spots.search_form')

            @if(isset( $keyword_name ))
                <h2 class="search-result"><span>{{ $keyword_name }}</span>の検索結果</h2>
            @else
                <h2 class="search-result">すべての投稿</h2>
            @endif

            <p class="search_count">{{ $spots->count() }}件<br></p>

            <div class="row">
                @include('spots.card')
            </div>

            @include('spots.count')
        </div>
    @endif
@endsection