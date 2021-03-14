@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            @include('spots.searches.search_form')

            <h2 class="search-result">{{ isset( $keyword_name ) ? $keyword_name . 'の検索結果' : '検索結果' }}</h2>

            <p class="search_count">{{ isset( $keyword_name ) ? $spots->count() . '件' : 'すべての投稿' }}<br></p>

            <div class="row">



                @if (!empty($spots))
                <div >
                    <table class="table table-hover">
                        <thead style="background-color: #ffd900">
                        <tr>
                            <th style="width:50%">商品名</th>
                            <th>商品カテゴリ</th>
                            <th>価格</th>
                            <th></th>
                        </tr>
                        </thead>
                        @foreach($spots as $spot)
                        <tr>
                        <td>{{ $spot->spot_name }}</td>
                        <td>
                            @foreach($spot->fishing_types as $fishing_type)
                                {{ $fishing_type->fishing_type_name }}
                            @endforeach
                        </td>
                        </tr>
                        @endforeach
                    </table>
                    </div>
                @endif
            </div>

            @include('spots.count')
        </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('/js/seeMore.js') }}" defer></script>
@endpush