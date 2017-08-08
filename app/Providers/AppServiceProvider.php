<?php

namespace AbuseIO\Providers;

use AbuseIO\Models\Event;
use AbuseIO\Models\Ticket;
use AbuseIO\Observers\EventObserver;
use AbuseIO\Observers\TicketObserver;
use Illuminate\Support\ServiceProvider;
use Log;

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
