@extends('layouts.app')

@section('content')
    <div class="login-signup_body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 login-signup">
                    <div class="login-signup-header">
                        <h1>ログイン</h1>
                    </div>

                    <div class="login-signup-form">
                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf

                            <label for="email">メールアドレス</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <p>{{ $message }}</p>
                                </span>
                            @enderror

                            <label for="password">パスワード</label>
                            <div class="login-signup-password">
                                <input id="password" type="password" class="js-password form-control @error('password') @enderror" name="password" autocomplete="current-password">
                                <input class="js-password-toggle" type="checkbox">
                                <div class="js-password-label"><i class="fas fa-eye fa-lg"></i></div>
                            </div>

                            <!-- <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            <br> -->

                            <button type="submit" class="login-signup-button">
                                {{ __('ログイン') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
