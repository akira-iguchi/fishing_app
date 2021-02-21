@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-8 user-edit_body mx-auto d-block ">
                    <h1>プロフィール編集</h1>
                    <div class="user_edit_form">
                        <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <label class="required">ユーザー名</label>
                            <div class="user-edit_text">
                                <input id="name" type="text" placeholder="お名前" name="name" value="{{ old('$user->name', $user->name) }}" autocomplete="name" autofocus>
                                <span><i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i></span>
                                @if($errors->has('name'))
                                    <span class="error_msg">
                                        <p>{{ $errors->first('name') }}</p>
                                    </span>
                                @endif
                            </div>

                            <div>
                                <label>プロフィール画像</label>
                                <input type="file" name="user_image">
                            </div>

                            <label class="required">メールアドレス</label>
                            <div class="user-edit_text">
                                <input id="email" type="email" placeholder="メールアドレス" name="email" value="{{ old('$user->email', $user->email) }}" autocomplete="email">
                                <span><i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i></span>
                                @if($errors->has('email'))
                                    <span class="error_msg">
                                        <p>{{ $errors->first('email') }}</p>
                                    </span>
                                @endif
                            </div>

                            <div>
                                <button class="user_edit-button">更新&emsp;<i class="fas fa-angle-right fa-lg"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection