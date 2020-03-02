<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'FMDB'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/smooth-products/css/smoothproducts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/jquery-ui/jquery-ui.min.css') }}">

    <link rel="stylesheet" href="{{ asset('vendor/jstree/themes/default/style.css') }}">
    @if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/select2.css') }}">
    @endif


    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/custom.css') }}">

    @if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/datatables.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.css') }}">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/responsive.bootstrap.min.css') }}">


    @yield('adminlte_css')
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <!-- <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/css.css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic') }}"> -->
	<link href="{{ asset('limitless/global_assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
	@include('vendor.adminlte.limitlessMode')
</head>

<body class="hold-transition @yield('body_class')">
    @yield('body')
    <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/getBrowser.js') }}"></script>
    <script src="{{ asset('vendor/smooth-products/js/smoothproducts.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/jquery-blockui/jquery.blockui.js') }}"></script>
    <script src="{{ asset('vendor/jstree/jstree.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/js/toastr.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/jquery.serialize-object.js') }}"></script>

    @if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> -->
    <script src="{{ asset('vendor/adminlte/js/select2.full.min.js') }}"></script>
    @endif

    @if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 renderer -->
    <script src="{{ asset('vendor/adminlte/js/datatables.min.js') }}"></script>
    @endif
    <script src="{{ asset('vendor/adminlte/js/moment.min.js') }}"></script>

    <script src="{{ asset('vendor/adminlte/plugins/datatables/app.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables/metronic.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables/datatable.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>

    @if(config('adminlte.plugins.chartjs'))
    <!-- ChartJS -->
    <script src="{{ asset('vendor/adminlte/js/Chart.bundle.min.js') }}"></script>
    @endif

    @yield('adminlte_js')
    <script>
        jQuery(window).on('load', function() {
            jQuery('.loading-event').fadeOut();
        });

        function logOut() {
            jQuery('#logout-form').submit()
        }

        window.onscroll = function() {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("scrToTop").style.display = "block";
            } else {
                document.getElementById("scrToTop").style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {

            var body = $("html, body");
            body.stop().animate({
                scrollTop: 0
            }, 500, 'swing', function() {

            });
        }
    </script>

</body>

</html>