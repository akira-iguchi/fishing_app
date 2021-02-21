@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row">
                <div class="mx-auto d-block col-lg-7 col-offset-lg-1 spot_container">
                    <p class="spot_created_at">{{ $spot->created_at->format('Y年m月d日') }}</p>
                    <h1 class="spot_name">{{ $spot->name }}</h1>
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div id="show_map"></div>
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/'.$spot->image) }}" alt="釣り場の画像">
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>

                    <table>
                        <tbody>
                            <tr>
                                <th>所在地</th>
                                <td>{{ $spot->address }}</td>
                            </tr>
                            <tr>
                                <th>説明</th>
                                <td>{{ $spot->explanation }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if (\Auth::id() === $spot->user_id)
                        <div class="spot_user_private">
                            <a href="{{ route('spots.edit', $spot->id)}}" class="spot_edit_link_button">編集</a>
                            <form action="{{route('spots.destroy', $spot->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="削除" class="spot_delete_button" onclick="return confirm('本当に削除しますか？')">
                            </form>
                        </div>
                    @endif
                    @include('spot_favorite.favorite_button')
                </div>

                <div class="mx-auto d-block col-lg-4 spot_creater">
                    <span>作成者</span><br>
                    <a href="{{ route('users.show', $spot->user_id)}}">
                        <img src="{{ asset('storage/'.$spot->user->user_image) }}" alt="釣り場投稿者の画像">
                        <p class="spot_creater_name">{{ $spot->user->name }}</p>
                    </a><br>
                </div>
            </div>
        </div>
    @endif
@endsection

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