<?php
//TODO - Move this array to a better place
$nav_items = array(
        'home'      => 'Home',
        'contacts'  => 'Contacts',
        'netblocks' => 'Netblocks',
        'domains'   => 'Domains',
        'reports'   => 'Reports',
        'search'    => 'Search',
        'analytics' => 'Analytics',
);


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ Config::get('app.name') }} {{ Config::get('app.version') }} - {{ $nav_items[Request::segment(2)] }}</title>

	<link href="{{ asset('/css/bootstrap.min.css')          }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-theme.min.css')    }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css')                  }}" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
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
                        @foreach($nav_items as $nav_link => $nav_title) {
                            <li class="{{ Request::path() == $nav_link ? 'active' : '' }}"><a href="{{ url('/'.Request::segment(1).'/'.$nav_link) }}">{{ $nav_title }}</a></li>
                        @endforeach
                </ul>
            </div>
        </div>
    </nav>

    @if (Session::has('message'))
        <div class="flash alert-info">
            <p>{{ Session::get('message') }}</p>
        </div>
    @endif

    @yield('content')

	<!-- Scripts -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
