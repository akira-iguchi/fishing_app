@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row spot_body">
                <div class="mx-auto d-block col-lg-8 spot_container">
                    <h1 class="spot_name">{{ $spot->spot_name }}</h1>

                    @foreach($spot->tags as $tag)
                        @if($loop->first)
                        <div class="card-body pt-0 pb-4 pl-3">
                            <div class="card-text line-height">
                        @endif
                            <a href="{{ route('tags.show', ['name' => $tag->name]) }}" class="spot_tag">
                                {{ $tag->hashtag }}
                            </a>
                        @if($loop->last)
                            </div>
                        </div>
                        @endif
                    @endforeach

                    <div class="swiper-container mb-2">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div id="show_map"></div>
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/'.$spot->spot_image) }}" alt="釣り場の画像">
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>

                    <div class="d-flex">
                        @include('favorites.favorite_button')
                        {{ $spot->created_at->format('Y/m/d') }}
                    </div>

                    <table>
                        <tbody>
                            <tr>
                                <th>所在地</th>
                                <td> {{ $spot->address ?: '未登録' }}</td>
                            </tr>
                            <tr>
                                <th><a href="/fishing_types">おすすめの釣り方</a></th>
                                @if($spot->fishing_types !== null)
                                    <td>
                                        @foreach($spot->fishing_types as $fishing_type)
                                            <ul class="spot-fishing_type">
                                                <li>{{ $fishing_type->fishing_type_name }}</li>
                                            </ul>
                                        @endforeach
                                    </td>
                                @else
                                    <td>未登録</td>
                                @endif
                                </tr>
                            <tr>
                                <th>説明</th>
                                <td>{{ $spot->explanation }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @include('spots.private')

                    <comments
                        :initial-count-comments='@json($spot->count_spot_comments)'
                        spot-id="{{ $spot->id }}"
                        user-id="{{ Auth::id() }}"
                    >
                    </comments>
                </div>

                <div class="mx-auto d-block col-lg-4">
                    <div class="spot_creater">
                        <span>作成者</span><br>
                        <a href="{{ route('users.show', $spot->user_id)}}">
                            <img src="{{ asset('storage/'.$spot->user->user_image) }}" alt="釣り場投稿者の画像">
                            <p class="spot_creater_name">{{ $spot->user->user_name }}</p>
                        </a>

                        <span><strong>{{ $spot->user->count_followings }}</strong>フォロー  <strong>{{ $spot->user->count_followers }}</strong>フォロワー</span>
                    </div>

                    @include('spots.cards.mini_card')
                </div>
            </div>
        </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('/js/imageSlide.js') }}" defer></script>
    <script src="{{ asset('/js/seeMore.js') }}" defer></script>
@endpush

<script>
    function initMap(){
        map = new google.maps.Map(document.getElementById('show_map'), { //'show_map'というidを取得してマップを表示
            center: {lat: {{ $spot->latitude }}, lng: {{ $spot->longitude }}},
            zoom: 15,
        });

        marker = new google.maps.Marker({ //GoogleMapにマーカーを落とす
            position:  {lat: {{ $spot->latitude }}, lng: {{ $spot->longitude }}}, //マーカーを落とす位置を決める（値はDBに入っている）
            map: map //マーカーを落とすマップを指定
        });
    }
</script>