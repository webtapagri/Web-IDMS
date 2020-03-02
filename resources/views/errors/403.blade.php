<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Login - {{ env('APP_NAME') }}</title>
	<link href="{{ asset('img/icon.ico') }}" rel="shortcut icon">

	<!-- Global stylesheets -->
	<!-- <link href="{{ asset('limitless/global_assets/css/css.css?family=Roboto:400,300,100,500,700,900') }}" rel="stylesheet" type="text/css"> -->
	<link href="{{ asset('limitless/global_assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/custom.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/global_assets/css/icons/fontawesome/styles.min.css') }}" rel="stylesheet" type="text/css">
	
	<style>
	.login-cover{
		background: url({{ asset('limitless/global_assets/images/backgrounds/user_bg1.png') }}) no-repeat;
		background-size: cover;
	}
	span.help-block {
		color: red !important;
	}
	</style>
	
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	@if(!@$data['alt'])
	<script src="{{ asset('limitless/global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('limitless/global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('limitless/global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<script src="{{ asset('limitless/global_assets/js/plugins/ui/ripple.min.js') }}"></script>
	<script src="{{ asset('limitless/global_assets/js/plugins/buttons/spin.min.js') }}"></script>
	<script src="{{ asset('limitless/global_assets/js/plugins/buttons/ladda.min.js') }}"></script>
	@endif
	
	<!-- /core JS files -->

	<!-- Theme JS files -->
	
	@if(!@$data['alt'])
	<script src="{{ asset('limitless/assets/js/app.js') }}"></script>
	@endif
	<!-- /theme JS files -->
</head>

<body>

	<!-- Page content -->
	<div class="page-content login-cover">

		<!-- Main content -->
		<div class="content-wrapper">

			<div class="content d-flex justify-content-center align-items-center">

				<!-- Container -->
				<div class="flex-fill">

					<!-- Error title -->
					<div class="text-center text-white mb-3">
						<h1 class="error-title">403</h1>
						<h5>Oops, an error has occurred. Page not found!</h5>
					</div>
					<!-- /error title -->


					<!-- Error content -->
					<div class="row">
						<div class="col-xl-4 offset-xl-4 col-md-8 offset-md-2">

							<!-- Buttons -->
							<div class="row">
								<div class="col-sm-12">
									<a href="{{ route('road') }}" class="btn btn-light btn-block mt-3 mt-sm-0 legitRipple">Go to Home</a>
								</div>
							</div>
							<!-- /buttons -->

						</div>
					</div>
					<!-- /error wrapper -->

				</div>
				<!-- /container -->

			</div>
		</div>
		<!-- /main content -->

	</div>
	
	<!-- /page content -->
	@yield('theme_js')
	
	@include('layouts.global_script')
	
	@yield('my_script')
	
	<script>
	$(document).ready(()=>{
		
		Ladda.bind('.btn-ladda-spinner', {
			dataSpinnerSize: 16,
			timeout: 2000
			});
	});
	</script>
</body>
</html>
