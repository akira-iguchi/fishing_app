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

                            <label for="user_name" class="required">ユーザー名</label>
                            <input id="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name') }}" autocomplete="user_name" placeholder="10文字以内" autofocus>
                            @error('user_name')
                                <span class="invalid-feedback" role="alert">
                                    <p>{{ $message }}</p>
                                </span>
                            @enderror

                            <label for="email" class="required">メールアドレス</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <p>{{ $message }}</p>
                                    </span>
                                @enderror

                            <label for="password" class="required">パスワード</label>
                            <div class="login-signup-password">
                                <input id="password" type="password" class="js-password form-control @error('password') @enderror" name="password" autocomplete="current-password" placeholder="4文字以上">
                                <input class="js-password-toggle" type="checkbox">
                                <div class="js-password-label"><i class="fas fa-eye fa-lg"></i></div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <p>{{ $message }}</p>
                                </span>
                            @enderror

                            <label for="password-confirm" class="required">パスワード（確認）</label>
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
