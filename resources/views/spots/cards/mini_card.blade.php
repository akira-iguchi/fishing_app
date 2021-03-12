<div class="other-spot">
    <hr>
    <h3 class="text-center mt-1">他の釣りスポット</h3>

    @foreach ($otherSpots as $spot)
        <div class="mini_card spot-hidden">
            <a href="{{ route('spots.show', $spot->id)}}">
                <div class="mini_card_img">
                    <!-- <img src="{{ $spot->image }}" alt="釣り場の画像"> -->
                    <img src="{{ asset('storage/'.$spot->spot_image) }}" alt="釣り場投稿者の画像">
                </div>
            </a>

            <div class="mini_card_content">
                <div class="card_spot_name">
                    {{ $spot->spot_name }}
                </div>

                <div class="mini_card_detail">

                    <div class="favorite_button">
                        @include('favorites.favorite_button')
                    </div>

                    <div class="card_comment">
                        <i class="fa fa-comment mr-1"></i>{{ $spot->count_spot_comments }}
                    </div>

                <a href="{{ route('users.show', $spot->user_id)}}">
                    <img src="{{ asset('storage/'.$spot->user->user_image) }}" alt="釣り場投稿者の画像">
                </a>
                </div>
            </div>
        </div>
    @endforeach

    <div>
        @include('spots.count', ['spots' => $otherSpots])
    </div>
</div>
