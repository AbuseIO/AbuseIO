<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ Config::get('app.name') }} {{ Config::get('app.version') }} - {{ trans_choice('misc.'.Request::segment(2), 2) }}</title>

	<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/flag-icon-min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/custom.css') }}" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="{{ asset('/js/html5shiv.min.js') }}"></script>
		<script src="{{ asset('/js/respond.min.js') }}"></script>
	<![endif]-->
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="https://abuse.io" target="_blank">AbuseIO</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                        @foreach(Config::get('main.interface.navigation') as $navLink)
                            <li class="{{ Request::path() == $navLink ? 'active' : '' }}">
								<a href="{{ url('/'.Request::segment(1).'/'.$navLink) }}">{{ trans('misc.'.$navLink) }}</a>
							</li>
                        @endforeach
                </ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-user"></span> {{ $user->fullName() . ' ( '  . $user->account->name .' )' }} <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li class="dropdown-header">{{ trans('misc.language') }}</li>
							@foreach(Config::get('app.locales') as $locale => $localeData)
							<li><a href="/admin/locale/{{$locale}}"><span class="flag-icon flag-icon-{{$localeData[1]}}"></span> {{ $localeData[0] }}</a></li>
							@endforeach
							<li role="separator" class="divider"></li>
							<li><a href="/admin/profile"><span class="glyphicon glyphicon-file"></span> {{ trans_choice('misc.profile', 2) }}</a></li>
							<li><a href="/auth/logout"><span class="glyphicon glyphicon-log-out"></span> {{ trans_choice('misc.logout', 2) }}</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> {{ trans('misc.settings') }} <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li class="dropdown-header">{{ trans('misc.options') }}</li>
							<li><a href="/admin/accounts"><span class="glyphicon glyphicon-tag"></span> {{ trans_choice('misc.accounts', 2) }}</a></li>
							<li><a href="/admin/brands"><span class="glyphicon glyphicon-tags"></span> {{ trans_choice('misc.brands', 2) }}</a></li>
							<li><a href="/admin/users"><span class="glyphicon glyphicon-user"></span> {{ trans_choice('misc.users', 2) }}</a></li>
						</ul>
					</li>
				</ul>
            </div>
        </div>
    </nav>
	<div class="container">
	@if (Session::has('message'))
	    <div class="alert alert-info alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p>{{ Session::get('message') }}</p>
	    </div>
	@endif
	@yield('content')
	</div>

	<!-- Location for all scripts -->
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
	<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
	@yield('extrajs')
</body>
</html>
