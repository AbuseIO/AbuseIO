<?php

namespace AbuseIO\Providers;

use DaveJamesMiller\Breadcrumbs\ServiceProvider;

/**
 * Class BreadcrumbsServiceProvider.
 */
class BreadcrumbsServiceProvider extends ServiceProvider
{
    public function registerBreadcrumbs()
    {
        require app_path().'/Http/Routes/Breadcrumbs.php';
    }
}
