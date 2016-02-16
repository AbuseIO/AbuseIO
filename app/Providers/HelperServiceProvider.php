<?php namespace AbuseIO\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class HelperServiceProvider
 *
 * @package AbuseIO\Providers
 */
class HelperServiceProvider extends ServiceProvider
{
    protected $helpers = [
        'castStringToBool',
        'castBoolToString',
        'isValidRegex',
        'hFileSize',
        'getDomain',
        'getUri',
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
                require_once($helper_file);
            }
        }
    }
}
