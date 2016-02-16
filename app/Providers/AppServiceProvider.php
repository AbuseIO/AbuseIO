<?php namespace AbuseIO\Providers;

use Illuminate\Support\ServiceProvider;
use Log;

/**
 * Class AppServiceProvider
 * @package AbuseIO\Providers
 */
class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (PHP_INT_SIZE !== 8) {
            Log::emergency(
                'You are running a 32bit PHP/OS system. You will need a 64bit PHP/OS to run this application'
            );
            dd();
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
