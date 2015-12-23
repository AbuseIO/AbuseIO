<?php namespace AbuseIO\Providers;

use Illuminate\Support\ServiceProvider;
use AbuseIO\Parsers\Factory as ParserFactory;
use AbuseIO\Collectors\Factory as CollectorFactory;
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
        $parserList = ParserFactory::getParsers();
        $this->buildConfig($parserList, 'parser');

        // Publish config files of all installed Collectors the same way
        $collectorList = CollectorFactory::getCollectors();
        $this->buildConfig($collectorList, 'collector');
    }

    private function buildConfig($list, $type)
    {
        foreach ($list as $handler) {
            $defaultConfig = [];
            $configOverride = [];
            $configKey = "{$type}s.{$handler}";

            $basePath = base_path() . "/vendor/abuseio/{$type}-" . strtolower($handler) . '/config';

            $defaultConfigFile = $basePath . "/{$handler}.php";
            if (File::exists($defaultConfigFile)) {
                $defaultConfig = include_once($defaultConfigFile);
            }

            $configOverrideFile = $basePath . '/' . app()->environment() . "/{$handler}.php";
            if (File::exists($configOverrideFile)) {
                $configOverride = include_once($configOverrideFile);
            }

            $this->app['config']->set($configKey, array_replace_recursive($defaultConfig, $configOverride));

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
