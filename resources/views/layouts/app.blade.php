<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Test') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        body.container-bg {
            width: 100vw;
            height: 100vh;
            background-image: url({{ asset('images/bg-pattern-light.svg') }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
    <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    @yield('css')
</head>

<body class="container-bg">
<div class="container-fluid">
    @include('include.navbar')
    <div class="container pb-5">
        @include('include.messages')
        @yield('content')
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/jquery/jquery.min.js') }}" type="application/javascript"></script>
<script src="{{ asset('js/bootstrap/bootstrap.min.js') }}" type="application/javascript"></script>
@yield('js')
</body>
</html>
