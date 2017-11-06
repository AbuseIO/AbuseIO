<nav class="col-sm-3 col-md-2 d-none d-sm-block navbar-dark bg-light sidebar">
    <ul class="nav nav-pills flex-column" role="tablist">
        @foreach(Config::get('main.interface.navigation') as $navName => $navIcon)
            <li class="nav-item">
                {!!
                    link_to_route_html(
                        'admin.'.$navName.'.index',
                        trans('misc.sidemenu.'.$navName). ((Request::segment(2) == $navName) ? '<span class="sr-only">(current)</span>' : ''),
                        //'<i class="material-icons">'.$navIcon.'</i>&nbsp; '.trans('misc.sidemenu.'.$navName). ((Request::segment(2) == $navName) ? '<span class="sr-only">(current)</span>' : ''),
                        [],
                        [
                        'class' => 'nav-link'.((Request::segment(2) == $navName) ? ' active' : '' )
                        ]
                    )
                !!}
            </li>
        @endforeach
    </ul>
</nav>
