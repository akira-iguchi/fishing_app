@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <form action="{{ url('spots/search')}}" method="post" class="spotIndex_search_form">
                {{ csrf_field()}}
                {{method_field('get')}}
                <input type="text" class="spotIndex_search_text" placeholder="キーワードを入力" name="name">
                <button type="submit" class="spotIndex_search_button"><i class="fas fa-search"></i></button>
            </form>

            <div class="row">
                @foreach ($spots as $spot)
                    <div class="mx-auto d-block col-lg-4 col-md-6">
                        <a href="{{ route('spots.show', $spot->id)}}" class="spot-hidden">
                            <div class="spot_card">
                                <div class="spot_card_img">
                                    <!-- <img src="{{ $spot->image }}" alt="釣り場の画像"> -->
                                    <img src="{{ asset('storage/'.$spot->image) }}" alt="釣り場投稿者の画像">
                                </div>

                                <div class="spot_card_content">
                                    <p>{{ $spot->name }}</p>
                                    <p>{{ $spot->address }}</p>
                                    <p>{{ $spot->explanation }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
            @if (count($spots) > 0)
                <div class="seeMore"><i class="fa fa-chevron-down"></i>&nbsp;続きを見る</div>
            @endif
        </div>
    @endif
@endsection