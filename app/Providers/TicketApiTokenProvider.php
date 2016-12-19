<?php

namespace AbuseIO\Providers;

use AbuseIO\Models\Ticket;
use Illuminate\Support\ServiceProvider;

class TicketApiTokenProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Ticket::saving(function ($ticket) {
            /* @var Ticket $ticket */
            $ticket->generateApiToken();
        });
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
