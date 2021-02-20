@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <div class="container">
        <div class="row">
            <div class="mx-auto d-block col-lg-10 col-md-11 spot_form">

                <h1>釣りスポット編集</h1>

                <input class="spot_search" id="address" type="text" placeholder="所在地を入力"/>
                <button onclick="codeAddress()" class="spot_search_button"><i class="fas fa-search"></i></button>

                <div id="map"></div>
                <p>マーカーを掴んで移動も可能だよ！</p>

                <form method="POST" action="{{ route('spots.update', $spot->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input id="spot_latitude" type="hidden" name="latitude" value="35.6594666">
                    <input id="spot_longitude" type="hidden" name="longitude" value="139.7005536">

                    <div class="form-group">
                        <div class="required">釣りスポット名</div>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('$spot->name', $spot->name) }}">

                        @if($errors->has('name'))
                            <span class="error_msg">
                                <p>{{ $errors->first('name') }}</p>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="required">所在地</div>
                        <input type="text" class="form-control" name="address" value="{{ old('$spot->address', $spot->address) }}">

                        @if($errors->has('address'))
                        <span class="error_msg">
                            <p>{{ $errors->first('address') }}</p>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>画像</label>
                        <input type="file" name="image">
                    </div>

                    <div class="form-group">
                        <div class="required">説明</div>
                        <textarea rows="6" id="textArea" class="form-control" name="explanation">{{ old('$spot->explanation', $spot->explanation) }}</textarea>
                        残り<span id="textLest">300</span>文字
                        <p id="textAttention" style="display:none; color:red;">入力文字数が多すぎます。</p>

                        @if($errors->has('explanation'))
                            <span class="error_msg">
                                <p>{{ $errors->first('explanation') }}</p>
                            </span>
                        @endif
                    </div>

                    <button class="spot-create-edit-button"><i class="fas fa-pencil-alt"></i>&thinsp;更新</button>

                    <button type="button" class="back_button" onclick="history.back()">戻る</button>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection