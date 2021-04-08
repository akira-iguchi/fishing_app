@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            @include('spots.searches.search_form')

            <h2 class="search-result">
                @if (isset($searchData[1]) || is_array($searchData[2]))
                    @if (isset($searchData[1]))
                        <span>{{ $searchData[1] }}</span>
                    @endif

                    @if (is_array($searchData[2]))
                        @foreach($searchFishingTypes as $fishing_type_name)
                            <span>{{ $fishing_type_name }}</span>
                        @endforeach
                    @endif
                    の検索結果
                @else
                    検索結果
                @endif
            </h2>

            <p class="search_count">{{ isset($searchData[1]) || is_array($searchData[2]) ? $spots->count() . '件' : 'すべての投稿' }}</p><br>

            <div class="row">
                @if (!empty($spots))
                    @include('spots.cards.card')
                @endif
            </div>

            @include('spots.seeMore')
        </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('/js/seeMore.js') }}" defer></script>
@endpush