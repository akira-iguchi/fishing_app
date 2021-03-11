<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Fishing Spot</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('/images/favicon.ico') }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css" />
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/spot.css') }}">
        <link rel="stylesheet" href="{{ asset('css/user.css') }}">
        <link rel="stylesheet" href="{{ asset('css/fishing_type.css') }}">
    </head>

    <body>

        {{-- ナビゲーションバー --}}
        @include('commons.navbar')

        {{-- フラッシュメッセージ --}}
        @include('commons.flash_message')

        <div id="app">
            <main>@yield('content')</main>
        </div>

        {{-- フッター --}}
        @include('commons.footer')

        <script src="{{ mix('/js/app.js') }}"></script>
        @stack('js')
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
        <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key={{ config('services.google-map.apikey') }}&callback=initMap"></script> -->
        <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    </body>
</html>