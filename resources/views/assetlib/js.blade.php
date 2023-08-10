<?php
$js_files = [
    'vendor-bundle' => [
        'path' => '/theme-assets/js/vendor.bundle.base.js',
        'required' => 0
    ],
    'datepicker' => [
        'path' => '/theme-assets/bootstrap-datepicker/bootstrap-datepicker.min.js',
        'required' => 0
    ],
    'Chart' => [
        'path' => '/theme-assets/chart.js/Chart.min.js',
        'required' => 0
    ],
    'progressbar' => [
        'path' => '/theme-assets/progressbar.js/progressbar.min.js',
        'required' => 0
    ],
    'off-canvas' => [
        'path' => '/js/off-canvas.js',
        'required' => 0
    ],
    'hoverable-collapse' => [
        'path' => '/js/hoverable-collapse.js',
        'required' => 0
    ],
    'template' => [
        'path' => '/js/template.js',
        'required' => 0
    ],
    'settings' => [
        'path' => '/js/settings.js',
        'required' => 0
    ],
    'todolist' => [
        'path' => '/js/todolist.js',
        'required' => 0
    ],
    'jquery-cookie' => [
        'path' => '/js/jquery.cookie.js',
        'required' => 0
    ],
    'dashboard' => [
        'path' => '/js/dashboard.js',
        'required' => 0
    ],

    'data-table' => [
        'path' => '/js/data-table.js',
        'required' => 0
    ],
    'roundedBarCharts' => [
        'path' => '/js/Chart.roundedBarCharts.js',
        'required' => 0
    ],
    'init' => [
        'path' => '/js/init.js',
        'required' => 1
    ],
    'ajax' => [
        'path' => '/js/ajax.js',
        'required' => 1
    ],
    'customjs' => [
        'path' => '/js/elite/custom.js',
        'required' => 1
    ],
    'nprogress' => [
        'path' => '/theme-assets/nprogress/nprogress.js',
        'required' => 1
    ],
    'jquery-file-upload' => [
        'path' => '/theme-assets/jquery-file-upload/jquery.uploadfile.min.js',
        'required' => 1
    ],
    'jquery-steps' => [
        'path' => '/theme-assets/jquery-steps/jquery.steps.min.js',
        'required' => 0
    ],
    'wizard' => [
        'path' => '/js/wizard.js',
        'required' => 0
    ],
    'sweetalert' => [
        'path' => '/theme-assets/sweetalert2/dist/sweetalert2.all.min.js',
        'required' => 0,
    ],
    'sweet-alert.init' => [
        'path' => '/theme-assets/sweetalert2/sweet-alert.init.js',
        'required' => 0,
        'depends' => ['sweetalert']
    ],
    'jquery.validate' => [
        'path' => '/js/jquery.validate.js',
        'required' => 0,
        'depends' => []
    ],

];

\App\Library\AssetLib::echoJsFiles($js_files);
