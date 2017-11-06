<nav id="header" class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" role="navigation">
    <a class="navbar-brand" href="/admin/home">{{ Config::get('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavTop" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavTop">
        <ul class="navbar-nav">
            <li class="nav-item">
                <!-- Search box -->
                <div class="input-group search_box mr-3">
                    <a class="nav-link input-group-addon search-label" href="#" ><span class="material-icons">search</span></a>
                    <input type="text" id="search_query" class="form-control bg-white" placeholder="Search" aria-label="Search" aria-describedby="search-label">
                </div>
            </li>
        </ul>

        <!-- Language menu -->
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLocale" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">translate</i>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownLocale">
                    <h6 class="dropdown-header">{{ trans_choice('misc.language', 2) }}</h6>
                    @foreach(Config::get('app.locales') as $locale => $localeData)
                        <a class="dropdown-item" href="/admin/locale/{{$locale}}">
                            @if ($locale == $auth_user->locale)
                                <i class="material-icons">check_box</i>
                            @else
                                <i class="material-icons">check_box_outline_blank</i>
                            @endif
                            {{ $localeData[0] }}
                        </a>
                    @endforeach
                </div>
            </li>
        </ul>
        <!-- Admin menu -->
        @if ($auth_user->hasRole('admin'))
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAdmin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">settings</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownAdmin">
                        <h6 class="dropdown-header">{{ trans('misc.settings') }}</h6>
                        {!! link_to_route_html('admin.accounts.index', '<i class="material-icons">domain</i>&nbsp; '. trans('misc.topmenu.accounts'), [], ['class' => 'dropdown-item']) !!}
                        {!! link_to_route_html('admin.brands.index', '<i class="material-icons">label</i>&nbsp; '. trans('misc.topmenu.brands'), [], ['class' => 'dropdown-item']) !!}
                        {!! link_to_route_html('admin.users.index', '<i class="material-icons">group</i>&nbsp; '. trans('misc.topmenu.users'), [], ['class' => 'dropdown-item']) !!}
                    </div>
                </li>
            </ul>
        @endif
        <!-- User menu -->
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">person</i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownUser">
                    <h6 class="dropdown-header">{{ $auth_user->fullName() . ' ('  . $auth_user->account->name .')' }}</h6>
                    {!! link_to_route_html('admin.profile.index', '<i class="material-icons">face</i>&nbsp; '. trans('misc.topmenu.profile'), [], ['class' => 'dropdown-item']) !!}
                    <a class="dropdown-item" href="/auth/logout"><i class="material-icons">exit_to_app</i>&nbsp; {{ trans('misc.topmenu.logout') }}</a>
                </div>
            </li>
        </ul>
    </div>
</nav>