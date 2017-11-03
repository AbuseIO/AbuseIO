<?php

namespace AbuseIO\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class HelperServiceProvider.
 */
class HelperServiceProvider extends ServiceProvider
{
    protected $helpers = [
        'ashAsset',
        'castBoolToString',
        'castStringToBool',
        'generateApiToken',
        'generatePassword',
        'getDomain',
        'getUri',
        'getUrlData',
        'hFileSize',
        'inetItop',
        'inetPtoi',
        'isValidRegex',
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // ..
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        foreach ($this->helpers as $helper) {
            $helper_file = app_path().'/Helpers/'.$helper.'.php';

            if (\File::isFile($helper_file)) {
                require_once $helper_file;
            }
        }
    }
}
