<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ Config::get('app.name') }} {{ Config::get('app.version') }} - {{ Config::get('main.interface.navigation.' . Request::segment(2)) }}</title>

    <link href="{{ asset('/css/custom.css')                 }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap.min.css')          }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-theme.min.css')    }}" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="{{ asset('/js/html5shiv.min.js') }}"></script>
		<script src="{{ asset('/js/respond.min.js') }}"></script>
	<![endif]-->

    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>

</head>
<body>
    <br><br><br>

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
                        @foreach(Config::get('main.interface.navigation') as $navLink => $navTitle)
                            <li class="{{ Request::path() == $navLink ? 'active' : '' }}"><a href="{{ url('/'.Request::segment(1).'/'.$navLink) }}">{{ $navTitle }}</a></li>
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

</body>
</html>
