@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            @include('spots.searches.search_form')

            <h2 class="search-result">
                @if (isset($searchWord) || is_array($fishingTypes))
                    @if (isset($searchWord))
                        <span>{{ $searchWord }}</span>
                    @endif

                    @if (is_array($fishingTypes))
                        @foreach($searchFishingTypes as $fishing_type_name)
                            <span>{{ $fishing_type_name }}</span>
                        @endforeach
                    @endif
                    の検索結果
                @else
                    検索結果
                @endif
            </h2>

            <p class="search_count">{{ isset($searchWord) || is_array($fishingTypes) ? $spots->count() . '件' : 'すべての投稿' }}</p><br>

            <div class="row">
                @if (!empty($spots))
                    @include('spots.cards.card')
                @endif
            </div>

            @include('spots.count')
        </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('/js/seeMore.js') }}" defer></script>
@endpush