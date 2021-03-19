@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-8 user-edit_body mx-auto d-block">
                    <h1>プロフィール編集</h1>
                    <div class="user_edit_form">
                        <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <label for="user_name" class="required">ユーザー名</label>
                            <div class="user-edit_text">
                                <input id="user_name" type="text" placeholder="お名前" name="user_name" value="{{ old('user_name', $user['user_name']) }}" autocomplete="user_name" autofocus>
                                <span><i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i></span>
                            </div>
                            @if($errors->has('user_name'))
                                <span class="error_msg">
                                    <p>{{ $errors->first('user_name') }}</p>
                                </span>
                            @endif

                            <div>
                                <label for="image1">プロフィール画像</label>
                                <input id="image1" type="file" name="user_image">
                                <p class="text-danger" id="file1_hidden">画像ファイルを選択してください</p>
                                <span><img id="file1-preview"></span>
                                @if($errors->has('user_image'))
                                    <span class="error_msg">
                                        <p>{{ $errors->first('user_image') }}</p>
                                    </span>
                                @endif
                            </div>

                            <label for="email" class="required">メールアドレス</label>
                            <div class="user-edit_text">
                                <input id="email" type="email" placeholder="メールアドレス" name="email" value="{{ old('email', $user['email']) }}" autocomplete="email">
                                <span><i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i></span>
                            </div>
                            @if($errors->has('email'))
                                <span class="error_msg">
                                    <p>{{ $errors->first('email') }}</p>
                                </span>
                            @endif

                            <label for="textAreaIntroduction">自己紹介</label>
                                <div class="user-edit_text">
                                    <textarea rows="5" id="textAreaIntroduction" name="introduction">{{ old('introduction', $user['introduction']) }}</textarea>
                                </div>
                                <p>残り<span id="textLestIntroduction">100</span>文字</p>
                                <p id="textAttentionIntroduction" style="display:none; color:red;">入力文字数が多すぎます。</p>
                                @error('introduction')
                                    <span class="invalid-feedback" role="alert">
                                        <p>{{ $message }}</p>
                                    </span>
                                @enderror

                            <div>
                                <button class="user_edit-button">更新&emsp;<i class="fas fa-angle-right fa-lg"></i></button>
                            </div>
                        </form>
                    </div>
                    <p class="back_link" onclick="history.back()">戻る</p>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('/js/wordCount.js') }}" defer></script>
    <script src="{{ asset('/js/imagePreview.js') }}" defer></script>
@endpush