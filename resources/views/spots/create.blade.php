@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <d class="mx-auto d-block col-lg-10 col-md-11 spot_form">

                <h1>釣りスポット作成</h1>

                <input class="spot_search" id="address" type="text" placeholder="所在地を入力"/>
                <button onclick="codeAddress()" class="spot_search_button"><i class="fas fa-search"></i></button>

                <div id="map"></div>
                <p>マーカーを掴んで移動も可能だよ！</p>

                <form method="POST" action="{{ route('spots.store') }}" enctype="multipart/form-data">
                    @csrf

                    @include('spots.form')

                    <button class="spot-create-edit-button"><i class="fas fa-pencil-alt"></i>&thinsp;投稿</button>

                    <button type="button" class="back_button" onclick="history.back()">戻る</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('/js/setLocation.js') }}" defer></script>
    <script src="{{ asset('/js/wordCount.js') }}" defer></script>
    <script src="{{ asset('/js/imagePreview.js') }}" defer></script>
@endpush