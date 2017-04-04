<?php

namespace AbuseIO\Providers;

use AbuseIO\Models\Ticket;
use DB;
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
        // register a saved event listener on Ticket
        // which will add or update the ash tokens only when empty
        Ticket::saved(function ($ticket) {
            $salt = env('APP_KEY');
            if (empty($ticket->ash_token_ip)) {
                $token = md5($salt . $ticket->id . $ticket->ip . $ticket->ip_contact_reference);
                DB::update("update tickets set ash_token_ip = ? where id = ?", [$token, $ticket->id]);
            }
            if (empty($ticket->ash_token_domain)) {
                $token = md5($salt . $ticket->id . $ticket->domain . $ticket->domain_contact_reference);
                DB::update("update tickets set ash_token_domain = ? where id = ?", [$token, $ticket->id]);
            }
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
