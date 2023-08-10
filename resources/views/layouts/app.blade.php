<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Safelink: Leading SD-WAN Solutions Provider') }}</title>
    <meta name="description" content="Experience seamless connectivity and optimized network performance with Safelink, your trusted SD-WAN solutions provider. Elevate your business infrastructure today.
">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @section('head_css')
        @include('assetlib.head_css')
    @show

    @section('head_js')
        @include('assetlib.head_js')
    @show
    <?php
    \App\Library\AssetLib::library('feather', 'materialdesignicons', 'themify-icons', 'typicons', 'simple-line-icons', 'vendor-bundle', 'dataTables-bootstrap4', 'dataTables', 'datepicker', 'Chart', 'progressbar', 'off-canvas', 'hoverable-collapse', 'settings', 'todolist', 'jquery-cookie', 'dashboard',  'roundedBarCharts', 'sweetalert','sweet-alert.init', 'jquery.validate'); //, 'template',  'data-table',
    ?>
    <!-- End plugin css for this page -->
    <!-- inject:css -->

    <!-- Scripts
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    -->
    <style>

        .sweet-alert {
            padding: 25px;
        }

        .sweet-alert h2 {
            margin-top: 0px;
        }

        .sweet-alert p {
            line-height: 30px;
        }

        .swal-wd .swal2-icon {
            display: block !important;
        }

        .swal-wd .swal2-content {
            display: block !important;
        }

        .swal-wd {
            width: auto !important;
            background: #333333 !important;
        }

        .swal-wd .swal2-buttonswrapper {
            margin-top: 10px;
        }

        .swal-wd .swal2-title {
            color: #ececec;
            font-size: 60px;
            margin-bottom: 0 !important;
            line-height: 20px;
            font-weight: 500;
            text-align: center;
        }

        .swal-wd .swal2-styled {

            font-size: 12px;
            font-weight: 500;
            margin: 0;
            padding: 3px 15px;
            margin-left: 10px;
        }

        .swal-wd .swal2-styled:focus {

            box-shadow: none;
        }

        .swal-clipboard {
            opacity: 0.7;
            width: auto !important;
            pointer-events: none;
        }

        .swal-clipboard .swal2-title {
            margin: 0;
        }

        /************** sweet Alert ******************/
        .swal-wd .swal2-icon {
            display: none !important;
        }

        .swal-wd .swal2-content {
            display: block !important;
        }

        .swal-wd {
            width: auto !important;
            background: #ffffff !important;
        }

        .swal-wd .swal2-buttonswrapper {
            margin-top: 10px;
        }

        .swal-wd .swal2-title {
            /*color: #5f93b2 !important;*/
            font-size: 40px !important;
            margin-bottom: 0 !important;
            line-height: 20px;
            font-weight: 400 !important;
            text-align: center;
        }

        .swal-wd .swal2-styled {

            font-size: 12px;
            font-weight: 500;
            margin: 0;
            padding: 3px 15px;
            margin-left: 10px;
        }

        .swal-wd .swal2-styled:focus {

            box-shadow: none;
        }

        .swal-clipboard {
            opacity: 0.7;
            width: auto !important;
            pointer-events: none;
        }

        .swal-clipboard .swal2-title {
            margin: 0;
        }

        .swal2-popup .swal2-styled.swal2-confirm {
            border: 0;
            border-radius: .25em;
            background: initial;
            background-color: #81c5ef;
            color: #fff;
            font-size: 0.7000em !important;
        }

        .swal2-popup .swal2-styled.swal2-cancel {
            border: 0;
            border-radius: .25em;
            background: initial;
            background-color: rgb(221, 51, 51);
            color: #fff;
            font-size: 0.7000em !important;
        }

        .swal2-popup .swal2-content {
            z-index: 1;
            justify-content: center;
            margin: 8px !important;
            padding: 0;
            color: #545454;
            font-size: 1.125em;
            font-weight: 300;
            line-height: normal;
            word-wrap: break-word;
        }

        button.swal2-confirm.btn.btn-info {
            border-radius: 20px;
            border: 2px;
        }

        button.swal2-cancel.swal2-styled {
            background-color: rgb(215, 63, 58);
            border-radius: 16px !important;
            height: 42px;
            font-size: 12px !important;
        }
        /*********************** sweet alert - end *************/

        body.loader_bg:before {
            content: "";
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            position: fixed;
            background: #000;
            z-index: 999;
            opacity: .6;
        }

        body.loader_bg .loader{
            display: block;
        }

        .loader {
            display: none;
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #3498db;
            width: 75px;
            height: 75px;
            animation: spin 2s linear infinite;
            margin: auto;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            position: fixed;
            z-index: 9999;
        }

        .loader1 {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            margin:auto;
            left:0;
            right:0;
            top:0;
            bottom:0;
            position:fixed;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }


    </style>
</head>
<body>

<div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <div class="horizontal-menu">
        @section('app-top-bar')
            @include('layouts.app-top-bar')
        @show

        @section('app-nav-menu')
            @include('layouts.app-nav-menu')
        @show

    </div>

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="container">
                    @yield('content')
                </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
        @section('app-footer')
            @include('layouts.app-footer')
        @show
        <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>

@section('footer_js')
    @include('assetlib.js')
@show


<!-- endinject -->

</body>
</html>
