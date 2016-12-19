<?php

namespace AbuseIO\Providers;

use AbuseIO\Models\Ticket;
use Illuminate\Support\ServiceProvider;

/**
 * Class AshServiceProvider.
 */
class AshServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // register a saving event listener on Ticket
        // which will add the ash tokens
        Ticket::saving(function ($ticket) {
            $salt = env('APP_KEY');
            $ticket->ash_token_ip = md5($salt.$ticket->id.$ticket->ip.$ticket->ip_contact_reference);
            $ticket->ash_token_domain = md5($salt.$ticket->id.$ticket->domain.$ticket->domain_contact_reference);
        });
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
