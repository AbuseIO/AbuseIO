<?php

namespace AbuseIO\Providers;

use AbuseIO\Collectors\Factory as CollectorFactory;
use AbuseIO\Notification\Factory as NotificationFactory;
use AbuseIO\Parsers\Factory as ParserFactory;
use Config;
use File;
use Illuminate\Support\ServiceProvider;

/**
 * Class ConfigServiceProvider.
 */
class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files of all installed Parsers
        // All parser configs we will put into the master 'parsers' tree of the config
        // So we can easily walk through all of them based on the active configuration
        $parserList = ParserFactory::getParsers();
        $this->buildConfig($parserList, 'parser');

        // Publish config files of all installed Collectors the same way
        $collectorList = CollectorFactory::getCollectors();
        $this->buildConfig($collectorList, 'collector');

        // Publish config files of all installed Collectors the same way
        $notificationList = NotificationFactory::getNotification();
        $this->buildConfig($notificationList, 'notification');

        /*
         * Provide a method to override the default configuration per environment. We only accept three named
         * environments called development, testing and production. Each of these environments has a directory
         * inside config where an config override can be made.
         */
        $envConfig = Config::get(app()->environment());
        if (!empty($envConfig) && is_array($envConfig)) {
            foreach ($envConfig as $configKey => $configElement) {
                $overrideConfig = $envConfig[$configKey];
                $defaultConfig = Config::get($configKey);

                if (is_array($defaultConfig) && is_array($overrideConfig)) {
                    $configWithOverrides = array_replace_recursive($defaultConfig, $overrideConfig);
                    Config::set($configKey, $configWithOverrides);
                }
            }
        }

        /*
         * Were updating the timezone manually because we are updating the config later then boot. Using method from:
         * vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/LoadConfiguration.php
         * This enforces the timezone we use in the override config file.
         */
        date_default_timezone_set(config('app.timezone'));
    }

    /**
     * Method to build different types of configuration trees.
     *
     * @param $list
     * @param $type
     */
    private function buildConfig($list, $type)
    {
        foreach ($list as $handler) {
            $defaultConfig = [];

            $configKey = "{$type}s.{$handler}";

            $basePath = base_path()."/vendor/abuseio/{$type}-".strtolower($handler).'/config';

            $defaultConfigFile = $basePath."/{$handler}.php";

            if (File::exists($defaultConfigFile)) {
                $defaultConfig = include $defaultConfigFile;
            }

            Config::set($configKey, $defaultConfig);
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
