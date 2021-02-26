<header tabindex="0"><a class="nav-title" href="/"><i class="fas fa-fish"></i>&thinsp;Fishing Spot</a>
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
                    {{-- ログアウトへのリンク --}}
                    <li>{!! link_to_route('logout.get', 'ログアウト') !!}</li>
                    {{-- 釣りスポット作成へのリンク --}}
                    <li><a href="{{ url('/spots/create') }}">投稿</a></li>
                    {{-- 釣りスポット作成へのリンク --}}
                    <li>
                        <a href="{{ route('users.show', Auth::id())}}">{{ Auth::user()->user_name }}さん</a>
                    </li>
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