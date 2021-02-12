@extends('layouts.app')

@section('content')
<div class="signout-body">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 signout">
        <div class="signout-header">
          <h1>Signup</h1>
        </div>
        <div class="signout-form">
          <form method="POST" action="{{ route('signup.post') }}" enctype="multipart/form-data">
            @csrf

            <h4 class="required">Username</h4>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
            @error('name')
              <span class="invalid-feedback" role="alert">
                <p>{{ $message }}</p>
              </span>
            @enderror

            <h4 class="required">Email</h4>
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <p>{{ $message }}</p>
                    </span>
                @enderror

            <h4 class="required">Password</h4>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <p>{{ $message }}</p>
                </span>
            @enderror

            <h4 class="required">Password_Confirmation</h4>
            <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="new-password">

            <button type="submit" class="signout-button">
                {{ __('Signup') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
