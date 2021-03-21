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
        <link rel="stylesheet" href="{{ asset('/css/fullcalendar/core/main.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/fullcalendar/daygrid/main.css') }}">
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

        <script>let weatherApiKey = "{{ config('services.weather.apikey') }}";</script>
        <script src="{{ mix('/js/app.js') }}"></script>
        @stack('js')
        <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key={{ config('services.google-map.apikey') }}&callback=initMap"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </body>
</html>