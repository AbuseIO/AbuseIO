<!DOCTYPE html>
<html lang="{{ $auth_user->locale }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('/favicon.ico') }}">
        <title>{{ Config::get('app.name') }}</title>

        <!-- Bootstrap core css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css') }}"/>

        <!-- dataTables css for bootstrap -->
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/dataTables.bootstrap.min.css') }}"/>

        <!-- Bootstrap Material Design Theme -->
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap-material-design.min.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/ripples.min.css') }}"/>

        <!-- Google Fonts needed -->
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/font-roboto.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/material-icons.css') }}"/>

        <!-- Localization flags -->
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/flag-icon-min.css') }}">

        <!-- Font-Awesome CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/font-awesome.min.css') }}">

        <!-- Custom css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/style.css') }}">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <nav class="navbar navbar-fixed-top">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                                    aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">{{ Config::get('app.name') }}</a>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <!-- Language menu -->
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">translate</i> <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-header">{{ trans('misc.topmenu.language') }}</li>
                                        @foreach(Config::get('app.locales') as $locale => $localeData)
                                            <li>
                                                <a href="/admin/locale/{{$locale}}"><span
                                                            class="flag-icon flag-icon-{{$localeData[1]}}"></span> {{ $localeData[0] }}
                                                    @if ($locale == $auth_user->locale)
                                                        <span class="fa fa-check"></span>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @if ($auth_user->hasRole('admin'))
                                <!-- Admin menu -->
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                           aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">settings</i> <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="dropdown-header">{{ trans('misc.topmenu.options') }}</li>
                                            <li>{!! link_to_route_html('admin.accounts.index', '<i class="material-icons">domain</i>&nbsp; '. trans('misc.topmenu.accounts')) !!}</li>
                                            <li>{!! link_to_route_html('admin.brands.index', '<i class="material-icons">label</i>&nbsp; '. trans('misc.topmenu.brands')) !!}</li>
                                            <li>{!! link_to_route_html('admin.users.index', '<i class="material-icons">group</i>&nbsp; '. trans('misc.topmenu.users')) !!}</li>
                                        </ul>
                                    </li>
                            @endif
                            <!-- User menu -->
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">person</i> <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-header">{{ $auth_user->fullName() . ' ('  . $auth_user->account->name .')' }}</li>
                                        <li>{!! link_to_route_html('admin.profile.index', '<i class="material-icons">face</i>&nbsp; '. trans('misc.topmenu.profile')) !!}</li>
                                        <li><a href="/auth/logout"><i class="material-icons">exit_to_app</i>&nbsp; {{ trans('misc.topmenu.logout') }}</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="row">
                <nav class="col-md-2 menu">
                    <ul>
                        @foreach(Config::get('main.interface.navigation') as $navName => $navIcon)
                            <li{!! (Request::segment(2) == $navName) ? ' class="active"' : '' !!}>
                                {!! link_to_route_html('admin.'.$navName.'.index', '<i class="fa '.$navIcon.' fa-lg fa-fw"></i>&nbsp; '.trans('misc.sidemenu.'.$navName)) !!}
                            </li>
                        @endforeach
                    </ul>
                </nav>
                <!-- Main content -->
                <div class="col-md-10 col-md-offset-2 main">
                    @if (Session::has('message'))
                        <div id="system_message" class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <p>{{ Session::get('message') }}</p>
                        </div>
                    @endif
                    @if(Request::segment(2) != 'home')
                        {!! Breadcrumbs::render() !!}
                    @endif
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Bootstrap core Javascript
        ================================================== -->
        <script type="text/javascript" src="{{ asset('/js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/popper.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/dataTables.bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/material.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/ripples.min.js') }}"></script>
        @yield('extrajs')
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="{{ asset('/js/html5shiv.min.js') }}"></script>
        <script src="{{ asset('/js/respond.min.js') }}"></script>
        <![endif]-->
        <script type="text/javascript" src="{{ asset('/js/global.js') }}"></script>
    </body>
</html>