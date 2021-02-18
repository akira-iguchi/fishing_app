@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <div class="row">
        <div class="mx-auto d-block col-lg-10 spot_content">
            <div class="spot-map-image">
                <div id="show_map"></div>
                <img src="{{ asset('/storage/'.$spot->image)}}" style="width: 100px;">
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