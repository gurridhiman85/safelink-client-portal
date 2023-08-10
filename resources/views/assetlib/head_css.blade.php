<?php
$css_files = [
    'feather' => [
        'path' => '/theme-assets/feather/feather.css',
        'required' => 1
    ],

    'materialdesignicons' => [
        'path' => '/theme-assets/mdi/css/materialdesignicons.min.css',
        'required' => 1
    ],

    'themify-icons' => [
        'path' => '/theme-assets/ti-icons/css/themify-icons.css',
        'required' => 1
    ],

    'typicons' => [
        'path' => '/theme-assets/typicons/typicons.css',
        'required' => 1
    ],

    'simple-line-icons' => [
        'path' => '/theme-assets/simple-line-icons/css/simple-line-icons.css',
        'required' => 1
    ],

    'vendor-bundle' => [
        'path' => '/theme-assets/css/vendor.bundle.base.css',
        'required' => 1
    ],

    'dataTables-bootstrap4' => [
        'path' => '/theme-assets/datatables.net-bs4/dataTables.bootstrap4.css',
        'required' => 1
    ],

    'dataTables' => [
        'path' => '/js/select.dataTables.min.css',
        'required' => 1
    ],

    'jquery-file-upload' => [
        'path' => '/theme-assets/jquery-file-upload/uploadfile.css',
        'required' => 1
    ],

    'font-awesome' => [
        'path' => '/theme-assets/font-awesome/css/font-awesome.min.css',
        'required' => 1
    ],

    'style' => [
        'path' => '/css/horizontal-layout-dark/style.css',
        'required' => 1
    ],
    'nprogress' => [
        'path' => '/theme-assets/nprogress/nprogress.css',
        'required' => 1
    ],


];
\App\Library\AssetLib::echoCssFiles($css_files);
