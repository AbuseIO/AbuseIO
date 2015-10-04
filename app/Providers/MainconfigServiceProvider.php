<?php

namespace AbuseIO\Providers;

use Illuminate\Support\ServiceProvider;

class MainconfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Provide a method to override the default configuration per environment. We only accept three named
         * environments called development, testing and production. Each of these environments has a directory
         * inside config where an config override can be made.
         */
        $envConfig = $this->app['config']->get(app()->environment());
        foreach ($envConfig as $configKey => $configElement) {
            $overrideConfig = $envConfig[$configKey];
            $defaultConfig = $this->app['config']->get($configKey, []);

            $this->app['config']->set($configKey, array_replace_recursive($defaultConfig, $overrideConfig));
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
