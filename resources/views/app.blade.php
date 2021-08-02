<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Fishing App</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet">
        @if( app('env')=='local' )
            <link rel="shortcut icon" href="{{ asset('/images/favicon.ico') }}">
            <link rel="stylesheet" href="{{ asset('css/app.css') }}">
            <link rel="stylesheet" href="{{ asset('css/spot.css') }}">
            <link rel="stylesheet" href="{{ asset('css/spot.css') }}">
            <link rel="stylesheet" href="{{ asset('css/user.css') }}">
            <link rel="stylesheet" href="{{ asset('css/fishing_type.css') }}">
            <link rel="stylesheet" href="{{ asset('css/fullcalendar/core/main.css') }}">
            <link rel="stylesheet" href="{{ asset('css/fullcalendar/daygrid/main.css') }}">
        @endif
        @if( app('env')=='production' )
            <link rel="shortcut icon" href="{{ secure_asset('/images/favicon.ico') }}">
            <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}">
            <link rel="stylesheet" href="{{ secure_asset('css/spot.css') }}">
            <link rel="stylesheet" href="{{ secure_asset('css/spot.css') }}">
            <link rel="stylesheet" href="{{ secure_asset('css/user.css') }}">
            <link rel="stylesheet" href="{{ secure_asset('css/fishing_type.css') }}">
            <link rel="stylesheet" href="{{ secure_asset('css/fullcalendar/core/main.css') }}">
            <link rel="stylesheet" href="{{ secure_asset('css/fullcalendar/daygrid/main.css') }}">
        @endif
    </head>

    <body>

        <div id="app"></div>

        <script src="{{ mix('/js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>
        <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    </body>
</html>