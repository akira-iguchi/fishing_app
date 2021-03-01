@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ url('spots/search')}}" method="post" class="spotIndex_search_form">
            {{ csrf_field()}}
            {{method_field('get')}}
            <input type="text" class="spotIndex_search_text" placeholder="キーワードを入力" name="name">
            <button type="submit" class="spotIndex_search_button"><i class="fas fa-search"></i></button>
        </form>

        <h2>{{ $tag->hashtag }}</h2>
        {{ $tag->spots->count() }}件<br>

        <div class="row">
            @foreach ($tag->spots as $spot)
                <div class="mx-auto d-block col-lg-4 col-md-6 col-9">
                    <div class="spot_card spot-hidden">
                        <a href="{{ route('spots.show', $spot->id)}}">
                            <div class="spot_card_img">
                                <!-- <img src="{{ $spot->image }}" alt="釣り場の画像"> -->
                                <img src="{{ asset('storage/'.$spot->spot_image) }}" alt="釣り場投稿者の画像">
                            </div>
                        </a>

                        <div class="spot_card_content">
                            <div class="spotName_userImage">
                                <p>{{ $spot->spot_name }}</p>
                                <a href="{{ route('users.show', $spot->user_id)}}">
                                    <img src="{{ asset('storage/'.$spot->user->user_image) }}" alt="釣り場投稿者の画像">
                                </a>
                            </div>

                            <p>{{ $spot->address }}</p>
                            <p>{{ $spot->explanation }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (count($tag->spots) > 3)
            <div class="seeMore"><i class="fa fa-chevron-down"></i>&nbsp;続きを見る</div>
        @endif
    </div>
@endsection