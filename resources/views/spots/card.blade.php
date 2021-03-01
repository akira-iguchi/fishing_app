@foreach ($spots as $spot)
    <div class="mx-auto d-block col-lg-4 col-md-6 col-9">
        <div class="spot_card spot-hidden">
            <a href="{{ route('spots.show', $spot->id)}}">
                <div class="spot_card_img">
                    <!-- <img src="{{ $spot->image }}" alt="釣り場の画像"> -->
                    <img src="{{ asset('storage/'.$spot->spot_image) }}" alt="釣り場投稿者の画像">
                </div>
            </a>

            <div class="spot_card_content">
                <div class="card_spot_name">
                    {{ $spot->spot_name }}
                </div>

                <div class="card_detail">

                    <div class="favorite_button">
                        <spot-favorite
                            :initial-is-liked-by='@json($spot->isLikedBy(Auth::user()))'
                            :initial-count-spot-favorites='@json($spot->count_spot_favorites)'
                            :authorized='@json(Auth::check())'
                            endpoint="{{ route('spots.favorite', ['spot' => $spot]) }}"
                            >
                        </spot-favorite>
                    </div>

                    <div class="card_comment">
                        <i class="fa fa-comment mr-1"></i>{{ $spot->spot_comments()->count() }}
                    </div>

                <a href="{{ route('users.show', $spot->user_id)}}">
                    <img src="{{ asset('storage/'.$spot->user->user_image) }}" alt="釣り場投稿者の画像">
                </a>
                </div>

                @if(isset( $spot->address ))
                    <p>{{ $spot->address }}</p>
                @endif
                <p>{{ $spot->explanation }}</p>
            </div>
        </div>
    </div>
@endforeach