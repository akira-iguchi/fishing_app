@extends('layouts.app')

@section('content')
    @if (Auth::check())
        {{ Auth::user()->name }}
    @else
    <div id="js-loading">
        <div class="js-spinner"></div>
    </div>

    <div class="top text-center">
        <h1 class="top-title">Fishing Spot</h1>
        <p>釣り場や釣り方を投稿して、<span>共有し合おう！</span></p>
        <a href="{{ url('/login') }}" class="login-button"><span><i class="fas fa-user-plus"></i>ログイン</span></a>
        <a href="{{ url('/signup') }}" class="signup-button"><span><i class="fas fa-sign-in-alt"></i>新規登録</span></a>
        <br>
        <a href="{{ route('login.guest') }}" class="guest_login-button"><span><i class="fas fa-sign-in-alt"></i>ゲストログイン</span></a>
    </div>
    @endif
@endsection

<script>
window.onload = function() {
    const spinner = document.getElementById('js-loading');
    spinner.classList.add('js-loaded');
}
</script>