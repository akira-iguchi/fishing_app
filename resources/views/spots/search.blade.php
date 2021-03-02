@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            @include('spots.search_form')


            <h2 class="search-result">{{ $keyword_name }}の検索結果</h2>


            <div class="row">
                @include('spots.card')
            </div>

            @include('spots.count')
        </div>
    @endif
@endsection