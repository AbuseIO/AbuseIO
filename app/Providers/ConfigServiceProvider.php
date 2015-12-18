<?php namespace AbuseIO\Providers;

use Illuminate\Support\ServiceProvider;
use AbuseIO\Parsers\Factory;
use File;

class ConfigServiceProvider extends ServiceProvider
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
        if (!empty($envConfig) && is_array($envConfig)) {
            foreach ($envConfig as $configKey => $configElement) {
                $overrideConfig = $envConfig[$configKey];
                $defaultConfig = $this->app['config']->get($configKey, []);

                $this->app['config']->set($configKey, array_replace_recursive($defaultConfig, $overrideConfig));
            }
        }

        /*
         * Were updating the timezone manually because we are updating the config later then boot. Using method from:
         * vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/LoadConfiguration.php
         * This enforces the timezone we use in the override config file.
         */
        date_default_timezone_set(config('app.timezone'));

        // Publish config files of all installed Parsers
        // All parser configs we will put into the master 'parsers' tree of the config
        // So we can easily walk through all of them based on the active configuration
        $parserList = Factory::getParsers();
        foreach ($parserList as $parser) {
            $basePath = base_path().'/vendor/abuseio/parser-'.strtolower($parser) . '/config';

            $parserConfig = $basePath . "/{$parser}.php";
            if (File::exists($parserConfig)) {
                $this->mergeConfigFrom(
                    $parserConfig,
                    'parsers.' . $parser
                );
            }

            $parserOverride = $basePath . '/' . app()->environment() . "/{$parser}.php";
            if (File::exists($parserOverride)) {
                $this->mergeConfigFrom(
                    $parserOverride,
                    app()->environment() . '.parsers.' . $parser
                );
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        config(
            [

            //

            ]
        );
    }
}
