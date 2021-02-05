<header tabindex="0"><a class="nav-title" href="/"><i class="fas fa-fish"></i>&thinsp;Fishing_Spot</a>
  <div id="nav-container">
    <div class="nav-bg"></div>
    <div class="nav-button" tabindex="0">
      <span class="nav-icon-bar"></span>
      <span class="nav-icon-bar"></span>
      <span class="nav-icon-bar"></span>
    </div>

    <div id="nav-content" tabindex="0">
      <ul>
        @if (Auth::check())
          {{-- ユーザ詳細ページへのリンク --}}
          <li><a href="#0">Home</a></li>
          {{-- ログアウトへのリンク --}}
          <li>{!! link_to_route('logout.get', 'Logout') !!}</li>
        @else
          {{-- ユーザ登録ページへのリンク --}}
          <li><a href="{{ url('/signup') }}"><i class="fas fa-user-plus"></i>&thinsp;Signup</a></li>
          {{-- ログインページへのリンク --}}
          <li><a href="{{ url('/login') }}"><i class="fas fa-sign-in-alt"></i>&thinsp;Login</a></li>
        @endif
      </ul>
    </div>
  </div>
</header>