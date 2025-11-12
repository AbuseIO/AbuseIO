<?php

namespace AbuseIO\Providers;

use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;
use AbuseIO\Models\Ticket;
use AbuseIO\Observers\EventObserver;
use AbuseIO\Observers\EvidenceObserver;
use AbuseIO\Observers\TicketObserver;
use Config;
use Illuminate\Support\ServiceProvider;
use Log;
use Request;
use URL;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     */
    public function boot(): void
    {
        /*
         * MARKNL:
         * OH NOES!! You've lost some bits somewhere! You need at least 64 of them buggers to run this application.
         */
        if (PHP_INT_SIZE < 8) {
            Log::emergency(
                'You will need a 64bit (or higher) PHP/OS to run this application'
            );
            dd();
        }

        // register observers
        Ticket::observe(TicketObserver::class);
        Event::observe(EventObserver::class);
        Evidence::observe(EvidenceObserver::class);

        // force the base url and schema to the configured APP_URL, otherwise use the current url
        $app_url = Request::getSchemeAndHttpHost();
        if (!empty(config('APP_URL'))) {
            $app_url = Config::get('app.url');
        }
        URL::useOrigin($app_url);

        // get the schema from the app_url and force it, fixes proxy errors in a ssl docker container
        if (preg_match('/^(http(s)?)/', $app_url, $matches, PREG_OFFSET_CAPTURE)) {
            // get the schema
            $schema = $matches[1][0];

            // enforce it in the routes
            URL::forceScheme($schema);
        }
    }

    /**
     * Register any application services.
     *
     */
    public function register(): void
    {
        // Provide a compatibility alias for Composer\Autoload\ClassMapGenerator used by older hooks
        if (!class_exists(\Composer\Autoload\ClassMapGenerator::class) && class_exists(\Composer\ClassMapGenerator\ClassMapGenerator::class)) {
            class_alias(\Composer\ClassMapGenerator\ClassMapGenerator::class, \Composer\Autoload\ClassMapGenerator::class);
        }
    }
}
