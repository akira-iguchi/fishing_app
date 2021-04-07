<div class="other-spot">
    <hr>
    <h3 class="text-center mt-1">他の釣りスポット</h3>

    @foreach ($otherSpots as $spot)
        <div class="mini_card">
            <a href="{{ route('spots.show', $spot->id)}}">
                <div class="mini_card_img">
                    <img src="{{ $spot->firstSpotImage() }}" alt="釣りスポットの画像">
                    <!-- <img src="{{ asset('storage/'.$spot->firstSpotImage()) }}" alt="釣りスポットの画像"> -->
                </div>
            </a>

            <div class="mini_card_content">
                <div class="card_spot_name">
                    {{ $spot->spot_name }}
                </div>

                <div class="mini_card_detail">

                    <div class="card_item">
                        @include('favorites.favorite_button')
                    </div>

                    <div class="card_item">
                        <i class="fa fa-comment mr-1"></i>{{ $spot->count_spot_comments }}
                    </div>

                <a href="{{ route('users.show', $spot->user_id)}}">
                    <img src="{{ $spot->user->user_image }}" alt="釣りスポット投稿者の画像">
                </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
