@foreach ($spots as $spot)
    <div class="mx-auto d-block col-xl-3 col-lg-4 col-md-6 col-11">
        <div class="spot_card spot-hidden">
            <a href="{{ route('spots.show', $spot->id)}}">
                <div class="spot_card_img">
                    <img src="{{ $spot->firstSpotImage() }}" alt="釣りスポットの画像">
                    <!-- <img src="{{ asset('storage/'.$spot->firstSpotImage()) }}" alt="釣りスポットの画像"> -->
                </div>
            </a>

            <div class="spot_card_content">
                <div class="card_spot_name">
                    {{ $spot->spot_name }}
                </div>

                <div class="card_detail">

                    <div class="card_item">
                        @include('favorites.favorite_button')
                    </div>

                    <div class="card_item mr-2">
                        <i class="fa fa-comment mr-1"></i>{{ $spot->count_spot_comments }}
                    </div>

                    <div class="card_item">
                        <i class="fas fa-clock"></i>{{ $spot->created_at->diffForHumans() }}
                    </div>

                <a href="{{ route('users.show', $spot->user_id)}}">
                    <img src="{{ $spot->user->user_image }}" alt="釣りスポット投稿者の画像">
                    <!-- <img src="{{ asset('storage/'.$spot->user->user_image) }}" alt="釣りスポット投稿者の画像"> -->
                </a>
                </div>

                @if(isset( $spot->address ))
                    <p>{{ $spot->address }}</p>
                @endif
                <!-- <p>{{ $spot->explanation }}</p> -->
            </div>
        </div>
    </div>
@endforeach