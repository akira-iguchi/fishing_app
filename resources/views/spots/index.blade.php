@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            @include('spots.searches.search_form')

            <div class="text-center">
                <a href="{{ url('/spots/create') }}" class="btn create_btn">釣りスポットを投稿</a>
            </div>

            <div class="toppage_under">
                <div class="w-100">
                    <h2 class="toppage_heading">人気の釣りスポット<i class="fas fa-crown"></i></h2>
                    <div class="row">
                        <!-- カードの大きさが違うため直書き -->
                        @foreach ($rankSpots as $spot)
                            <div class="mx-auto d-block col-md-6 col-11">
                                <div class="spot_card spot-hidden">
                                    <a href="{{ route('spots.show', $spot->id)}}">
                                        <div class="spot_card_img">
                                            <img src="{{ $spot->firstSpotImage() }}" alt="釣り場の画像">
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

                                            <div class="card_item mr-3">
                                                <i class="fa fa-comment mr-1"></i>{{ $spot->count_spot_comments }}
                                            </div>

                                            <div class="card_item">
                                                <i class="fas fa-clock"></i>&thinsp;{{ $spot->created_at->diffForHumans() }}
                                            </div>

                                        <a href="{{ route('users.show', $spot->user_id)}}">
                                            <img src="{{ $spot->user->user_image }}" alt="釣りスポットの画像">
                                            <!-- <img src="{{ asset('storage/'.$spot->user->user_image) }}" alt="釣り場投稿者の画像"> -->
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
                    </div>
                </div>

                <aside class="aside_hidden">
                    <select id="js-prefectures">
                        @include('weathers.prefecture')
                    </select>

                    <div class="entire_weather">
                        <div id="city-name"></div>
                        <div id="weather"></div>
                    </div>
                </aside>
            </div>

            <hr>

            @if (Auth::user()->count_followings !== 0)
            <h2 class="toppage_heading">フォローしたユーザーの投稿</h2>
                <div class="row">
                    @include('spots.cards.card', ['spots' => $followUserSpots])
                </div>
            <hr>
            @endif


            <h2 class="toppage_heading">最近の投稿</h2>
            <div class="row">
                @include('spots.cards.card', ['spots' => $recentSpots])
            </div>
            @include('spots.seeMore', ['spots' => $recentSpots])

        </div>

        @push('js')
            <script src="{{ asset('/js/weather.js') }}" defer></script>
            <script src="{{ asset('/js/seeMore.js') }}" defer></script>
        @endpush
    @else
        <div id="js-loading">
            <div class="js-spinner"></div>
        </div>

        <div class="top text-center">
            <h1 class="top-title">Fishing Spot</h1>
            <p>釣り場を投稿して、<span>共有し合おう！</span></p>
            <a href="{{ url('/login') }}" class="top-login-button"><span><i class="fas fa-user-plus mr-1"></i>ログイン</span></a>
            <a href="{{ url('/signup') }}" class="top-signup-button"><span><i class="fas fa-sign-in-alt mr-1"></i>新規登録</span></a>
            <br>
            <a href="{{ route('login.guest') }}" class="top-guest_login-button"><span><i class="fas fa-sign-in-alt mr-1"></i>ゲストログイン</span></a>
        </div>

        <div class="top-slider">
            <div class="spot-intro_image">
                <img src="/images/fishing_boat_man.png" alt="釣り画像">
            </div>

            <div class="spot-intro_expla">
                <p>Fishing Spotとは？</p>
                <p>
                    &emsp;Fishing Spotとは、釣り場を投稿し、釣り場にコメントして釣果などを共有するアプリです。また、釣り場におすすめの釣り方を選択することもできます。
                    さらに、カレンダーで釣りの予定、記録をすることができ、このアプリ１つで満足できます。
                    <br>
                    &emsp;最近は、釣りの技術が進み、釣りを始める人も多くなっています。そこで、釣り初心者の方でもこのアプリ1つで釣りを知り、楽しんでもらえるように、このアプリを作成しました。
                </p>
            </div>
        </div>

        <div class="top-slider">
            <div class="self-intro_expla">
                <p>自己紹介</p>
                <img src="/images/akira.jpeg" alt="自己紹介の画像">
                <p>
                    &emsp;井口 晶。19歳。プログラミングに励む、田舎好きな大阪生まれ育ちの都会男子です。関西大学第一高等学校入学。そのまま関西大学法学部へ進学(現在1年生)。
                    <br>
                    &emsp;趣味は、釣りと筋トレ。釣りで自然と戯れつつ、筋トレで自分を追い込んでます。
                </p>
            </div>

            <div class="self-intro_image">
                <img src="/images/akira.jpeg" alt="自己紹介の画像">
            </div>
        </div>

        @push('js')
            <script src="{{ asset('/js/loading.js') }}" defer></script>
        @endpush
    @endif
@endsection