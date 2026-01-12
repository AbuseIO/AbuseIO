<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{ Config::get('app.name') }}</title>
		<!-- Bootstrap core css -->
		<link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css') }}"/>

		<!-- dataTables css for bootstrap -->
		<link rel="stylesheet" type="text/css" href="{{ asset('/css/dataTables.bootstrap5.min.css') }}"/>

		<!-- Localization flags -->
		<link rel="stylesheet" type="text/css" href="{{ asset('/css/flag-icon-min.css') }}">

		<!-- Font Awesome icons (local) -->
		<link rel="stylesheet" type="text/css" href="{{ asset('/css/font-awesome.min.css') }}">

		<!-- Custom css -->
		<link rel="stylesheet" type="text/css" href="{{ asset('/css/custom.css') }}">
	</head>
	<body>
	    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
	        <div class="container-fluid">
	            <div class="navbar-header">
	                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target=".navbar-collapse">
	                    <span class="visually-hidden">Toggle navigation</span>
	                    <span class="navbar-toggler-icon"></span>
	                </button>
	                <a class="navbar-brand" href="https://abuse.io" target="_blank">{{ Config::get('app.name') }}</a>
	            </div>
	            <div class="navbar-collapse collapse">
	                <ul class="navbar-nav me-auto">
	                    @foreach(Config::get('main.interface.navigation') as $navLink)
	                    <li class="nav-item">
							<a class="nav-link {{ Request::path() == $navLink ? 'active' : '' }}" href="{{ url('/admin/'.$navLink) }}">{{ trans('misc.'.$navLink) }}</a>
						</li>
	                    @endforeach
	                </ul>
					<ul class="navbar-nav ms-auto">
						@if (auth()->check() && auth()->user()->hasRole('admin'))
						<li class="nav-item dropdown">
							<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> {{ trans('misc.settings') }}</a>
							<ul class="dropdown-menu dropdown-menu-end">
								<li class="dropdown-header">{{ trans('misc.options') }}</li>
								<li><a class="dropdown-item" href="/admin/accounts"><i class="fa fa-tag"></i> {{ trans_choice('misc.accounts', 2) }}</a></li>
								<li><a class="dropdown-item" href="/admin/brands"><i class="fa fa-tags"></i> {{ trans_choice('misc.brands', 2) }}</a></li>
								<li><a class="dropdown-item" href="/admin/users"><i class="fa fa-user"></i> {{ trans_choice('misc.users', 2) }}</a></li>
							</ul>
						</li>
						@endif
						@if (auth()->check())
					<li class="nav-item dropdown">
						@php($currentLocale = Session::get('locale', auth()->user()->locale ?? Config::get('app.locale')))
						@php($currentFlag = (Config::get('app.locales')[$currentLocale][1] ?? $currentLocale))
						<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="flag-icon flag-icon-{{ $currentFlag }}"></span> {{ auth()->user()->first_name ?? '' }} {{ auth()->user()->last_name ?? '' }}
						</a>
					<ul class="dropdown-menu dropdown-menu-end">
						<li class="dropdown-header">{{ trans('misc.language') }}</li>
						@php($locales = Config::get('app.locales'))
						@foreach($locales as $code => $meta)
							<li class="{{ (Session::get('locale', Config::get('app.locale')) === $code) ? 'active' : '' }}">
								<a class="dropdown-item" href="{{ route('admin.locale', ['locale' => $code]) }}">
									<span class="flag-icon flag-icon-{{ $meta[1] }}"></span> {{ $meta[0] }}
								</a>
							</li>
						@endforeach
						<li><hr class="dropdown-divider"></li>
						<li><a class="dropdown-item" href="{{ route('admin.profile.index') }}">{{ trans('misc.profile') }}</a></li>
						<li><a class="dropdown-item" href="{{ url('/auth/logout') }}">{{ trans('misc.button.logout') }}</a></li>
					</ul>
					</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
		<div class="container-fluid app-container">
			@if (Session::has('message'))
			    <div class="alert alert-info alert-dismissible">
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			        <p>{{ Session::get('message') }}</p>
			    </div>
			@endif
			@yield('content')
		</div>

		<!-- Bootstrap Javascript ---------------------------->
		<script type="text/javascript" src="{{ asset('/js/jquery.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/js/dataTables.bootstrap5.min.js') }}"></script>
		@yield('extrajs')
	</body>
</html>
