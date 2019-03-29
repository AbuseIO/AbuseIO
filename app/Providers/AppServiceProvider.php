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
     * @return void
     */
    public function boot()
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
        if (!empty(env('APP_URL'))) {
            $app_url = Config::get('app.url');
        }
        URL::forceRootUrl($app_url);

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
     * @return void
     */
    public function register()
    {
        //
    }
}
