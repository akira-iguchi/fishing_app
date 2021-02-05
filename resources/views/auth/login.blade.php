@extends('layouts.app')

@section('content')
<div class="signout-body">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 signout">
        <div class="signout-header">
          <h1>Login</h1>
        </div>
        <div class="signout-form">
          <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <h4>Email:</h4>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Username">
            @error('email')
              <span class="invalid-feedback" role="alert">
                <p>{{ $message }}</p>
              </span>
            @enderror

            <h4>Password:</h4>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Password">
            @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror

            <!-- <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
            <br> -->

            <button type="submit" class="signout-button">
                {{ __('Login') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
