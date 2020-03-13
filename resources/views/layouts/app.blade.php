<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FixedPlus - Bootstrap Admin Dashboard Template</title>
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/icon.png') }} ">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/pagina.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style type="text/css">
        html,body{
            height: 100%;
        }
    </style>
</head>
<body @if(request()->is('/') || request()->is('login')) class="bg-light" @else class="" @endif >
    <div id="app" @if(request()->is('/') || request()->is('login')) class="misc-wrapper" @else class="" @endif>
        @yield('content')
    </div>
</body>
</html>
