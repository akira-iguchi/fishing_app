@extends('layouts.app')

@section('content')
<div class="login-container">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 login">
        <div class="login-header">
          <h1>Login</h1>
        </div>
        <div class="login-form">
            <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <h3>Username:</h3>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Username">
            <h3>Password:</h3>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Password">
            <!-- <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
            <br> -->
            <button type="submit" class="login-button">
                {{ __('Login') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
