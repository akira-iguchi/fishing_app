@extends('layouts.app')

@section('content')
    <div class="container fishing_type_body">
        <h1 class="fishing_type_title">釣り方一覧</h1>
        <div class="row">
            @foreach ($fishing_types as $fishing_type)
            <div class="mx-auto d-block col-lg-5 fishing_type_card">
                <span class="fishing_type_card_title">{{ $fishing_type->fishing_type_name }}</span>
                {{ $fishing_type->content }}
                <p class="text-center"><img onClick="openImageByFullScreen(this)" src="{{ asset('storage/'.$fishing_type->fishing_type_image) }}" alt="釣り場方の画像"></p>
                <small>画像クリックで拡大！</small>
                <hr>
                <h5>おすすめの釣り場</h5>
                @foreach ($fishing_type->spots as $spot)
                    <ul class="fishing_type-spot">
                        <li><a href="{{ route('spots.show', $spot->id) }}">{{ $spot->spot_name }}</a></li>
                    </ul>
                @endforeach
            </div>
            @endforeach

        </div>
    </div>
@endsection


<script>
  // フルスクリーンにする
  function openImageByFullScreen(e) {
  if (e.requestFullscreen) {
    e.requestFullscreen();
  } else if (e.mozRequestFullScreen) { /* Firefox */
    e.mozRequestFullScreen();
  } else if (e.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
    e.webkitRequestFullscreen();
  } else if (e.msRequestFullscreen) { /* IE/Edge */
    e.msRequestFullscreen();
  }
}
</script>