<?php

namespace AbuseIO\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class HelperServiceProvider.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(
            'AbuseIO\Repositories\Contracts\UserRepositoryInterface',
            'AbuseIO\Repositories\Eloquent\UserRepository'
        );
        $this->app->bind(
            'AbuseIO\Repositories\Contracts\AccountRepositoryInterface',
            'AbuseIO\Repositories\Eloquent\AccountRepository'
        );
        $this->app->bind(
            'AbuseIO\Repositories\Contracts\RoleRepositoryInterface',
            'AbuseIO\Repositories\Eloquent\RoleRepository'
        );
    }
}
