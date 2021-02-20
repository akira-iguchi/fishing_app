@extends('layouts.app')

@section('content')
<div class="login-signup_body">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 login-signup">
        <div class="login-signup-header">
          <h1>新規登録</h1>
        </div>
        <div class="login-signup-form">
          <form method="POST" action="{{ route('signup.post') }}">
            @csrf

            <h4 class="required">ユーザー名</h4>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
            @error('name')
              <span class="invalid-feedback" role="alert">
                <p>{{ $message }}</p>
              </span>
            @enderror

            <h4 class="required">メールアドレス</h4>
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <p>{{ $message }}</p>
                    </span>
                @enderror

            <h4 class="required">パスワード</h4>
            <div class="login-password">
                <input id="password" type="password" class="js-password form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="4文字以上">
                <input class="js-password-toggle" type="checkbox">
                <label class="js-password-label"><i class="fas fa-eye fa-lg"></i></label>
                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <p>{{ $message }}</p>
                  </span>
                @enderror
            </div>

            <h4 class="required">パスワード（確認）</h4>
            <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="new-password">

            <button type="submit" class="login-signup-button">
                {{ __('登録') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
