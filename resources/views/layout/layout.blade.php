<!DOCTYPE html>
    <!--
    This is a starter template page. Use this page to start your new project from
    scratch. This page gets rid of all links and provides the needed markup only.
    -->
    <html>
    <head>
        <meta charset="UTF-8">
        <title>{{ config('koi.app.name') }} :: @yield('title')</title>
        <link rel="icon" type="image/png" href="{{ asset(config('koi.app.image.logo')) }}" />

        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        {{-- Secure tags TuProyecto --}}
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        {{-- Vendor css --}}
        <link href="{{ asset("/css/vendor.min.css") }}" rel="stylesheet" type="text/css" />
        {{-- App css --}}
        <link href="{{ asset("/css/app.min.css") }}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-red-light sidebar-mini {{ Cookie::get('sidebar_toggle') }}">
        <div class="wrapper">

            {{-- Main Header --}}
            @include('layout.header')

            {{-- Content Wrapper. Contains page content --}}
            <div class="content-wrapper">
                @yield('content')

            </div>

            <!-- Main Footer -->
            @include('layout.footer')

        </div>

        <script>window.document.url = "{{ URL::to('/') }}";</script>
        {{-- Vendor KOI App --}}
        <script src="{{ asset ("/js/vendor.min.js") }}" type="text/javascript"></script>
        {{-- KOI App --}}
        <script src="{{ asset ("/js/app.min.js") }}" type="text/javascript"></script>
    </body>
</html>