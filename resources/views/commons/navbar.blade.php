@if (Auth::check())
<header tabindex="0"><a class="nav-title" href="/spots"><i class="fas fa-fish"></i>&thinsp;Fishing Spot</a>
@else
<header tabindex="0"><a class="nav-title" href="/"><i class="fas fa-fish"></i>&thinsp;Fishing Spot</a>
@endif
    <nav class="nav-container">
        <nav class="nav-bg"></nav>
        <nav class="nav-button" tabindex="0">
            <span class="nav-icon-bar"></span>
            <span class="nav-icon-bar"></span>
            <span class="nav-icon-bar"></span>
        </nav>

        <nav class="nav-content" tabindex="0">
            <ul>
                @if (Auth::check())
                    {{-- 釣りスポット作成へのリンク --}}
                    <li><a href="{{ url('/spots/create') }}">作成</a></li>
                    {{-- ログアウトへのリンク --}}
                    <li>{!! link_to_route('logout.get', 'ログアウト') !!}</li>
                @else
                    {{-- ユーザ登録ページへのリンク --}}
                    <li><a href="{{ url('/signup') }}"><i class="fas fa-user-plus"></i>&thinsp;新規登録</a></li>
                    {{-- ログインページへのリンク --}}
                    <li><a href="{{ url('/login') }}"><i class="fas fa-sign-in-alt"></i>&thinsp;ログイン</a></li>
                @endif
            </ul>
        </nav>
    </nav>
</header>