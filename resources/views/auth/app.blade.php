<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ Config::get('app.name') }} {{ Config::get('app.version') }} - {{ trans('misc.'.Request::segment(2)) }}</title>

		<!-- Bootstrap core css -->
		<link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css') }}"/>

		<!-- Bootstrap Material Design Theme -->
		<link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap-material-design.min.css') }}"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('/css/ripples.min.css') }}"/>

		<!-- Google Fonts needed -->
		<link rel="stylesheet" type="text/css" href="{{ asset('/css/font-roboto.css') }}"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('/css/material-icons.css') }}"/>

		<link href="{{ asset('/css/flag-icon-min.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/style.css') }}" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			@if (Session::has('message'))
				<div class="alert alert-info alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<p>{{ Session::get('message') }}</p>
				</div>
			@endif
			@yield('content')
		</div>

		<!-- Bootstrap core Javascript
		================================================== -->
		<script type="text/javascript" src="{{ asset('/js/jquery.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/js/popper.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/js/material.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/js/ripples.min.js') }}"></script>
		@yield('extrajs')
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="{{ asset('/js/html5shiv.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/js/respond.min.js') }}"></script>
		<![endif]-->
		<script type="text/javascript" src="{{ asset('/js/global.js') }}"></script>
	</body>
</html>
