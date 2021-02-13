@extends('layouts.app')

@section('content')
    @if (Auth::check())
        {{ Auth::user()->name }}
    @else
    <!-- <div id="js-loading">
        <div class="js-spinner"></div>
    </div> -->

    <div class="top text-center">
        <h1 class="top-title">Fishing Spot</h1>
        <p>釣り場や釣り方を投稿して、<span>共有し合おう！</span></p>
        <a href="{{ url('/login') }}" class="top-login-button"><span><i class="fas fa-user-plus"></i>ログイン</span></a>
        <a href="{{ url('/signup') }}" class="top-signup-button"><span><i class="fas fa-sign-in-alt"></i>新規登録</span></a>
        <br>
        <a href="{{ route('login.guest') }}" class="top-guest_login-button"><span><i class="fas fa-sign-in-alt"></i>ゲストログイン</span></a>
    </div>

    <div class="container top-intro">
        <div class="top-intro_image">
            <img src="/images/fishing_boat_man.png" alt="釣り画像">
        </div>
        <div class="top-intro_expla">
            <p>Fishing Spotとは？</p>
            <p>
                &emsp;Fishing Spotとは、釣り場を投稿し、釣り場にコメントして釣果などを共有したり、その釣り場におすすめの釣り方を投稿するアプリです。また、カレンダーに釣りの予定を入れたり、記録することができます。
                <br>
                &emsp;最近は、釣りの技術が進み、釣りを始める人も多くなっています。そこで、釣り初心者の方でもこのアプリ1つで釣りを知り、楽しんでもらえるように、このアプリを作成しました。
            </p>
        </div>
    </div>

    <div class="container top-intro" id="js">
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
    @endif
@endsection

<script>

    const targetElement = document.getElementById('js');
    console.log(targetElement);



</script>