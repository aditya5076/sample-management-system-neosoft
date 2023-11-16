<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sample-Management-System') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- Fonts -->
    <!-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->

    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <link href="{{ asset('public/default/css/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors -->
    <link href="{{ asset('public/default/css/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/default/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/webfonts/fa-solid-900.ttf" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet" />
    <!--end::Base Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/default/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/default/emoticons/stylesheet.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/default/css/neo.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/default/css/dataTables.bootstrap4.min.css') }}">
    <link href="{{ asset('public/default/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/default/css/sweetalert.css') }}" rel="stylesheet">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('public/default/css/custom-sms.css?version='.env('JS_CSS_VERSION')) }}">
    <link rel="shortcut icon" href="{{ asset('public/default/images/login_logo.png') }}" />
</head>
@guest

<body>
    @else

    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default m-brand--minimize m-aside-left--minimize">
        @include('layouts.sidebar')
        @endguest
        @yield('content')

        <div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
            <i class="la la-arrow-up"></i>
        </div>
        <script src="{{ asset('public/default/js/jquery.js') }}"></script>
        <script src="{{ asset('public/default/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/default/js/vendors.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ asset('public/default/js/scripts.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ asset('public/default/js/jquery.timeago.js') }}" type="text/javascript"></script>
        <!--end::Base Scripts -->
        <!--begin::Page Vendors -->
        <script src="{{ asset('public/default/js/fullcalendar.bundle.js') }}" type="text/javascript"></script>
        <!--end::Page Vendors -->
        <!--begin::Page Snippets -->
        <script src="{{ asset('public/default/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('public/default/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('public/default/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
        <script src="{{ asset('public/default/js/bootstrap-timepicker.js') }}" type="text/javascript"></script>
        <script src="{{ asset('public/default/js/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <!-- <script src="{{ asset('public/default/js/dashboard.js') }}" type="text/javascript"></script> -->
        <!-- <script src="{{ asset('public/default/js/select2.js') }}" type="text/javascript"></script> -->
        <script src="{{ asset('public/default/js/sweetalert.min.js') }}" type="text/javascript"></script>
        <script type="text/javascript" src="{{ asset('public/default/js/script.js') }}"></script>
        @yield('scripts')
        </div>
        @guest

        @else
        @include('layouts.footer')
        @endguest
    </body>

</html>