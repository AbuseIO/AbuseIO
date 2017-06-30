<?php

namespace AbuseIO\Observers;

use AbuseIO\Hook\Common as Hooks;
use AbuseIO\Models\Ticket;
use DB;

class TicketObserver
{
    /**
     * Listen to the Ticket saving event.
     *
     * @param Ticket $ticket
     *
     * @return void
     */
    public function saving(Ticket $ticket)
    {

        // generate the ApiToken
        if (empty($ticket->api_token)) {
            $ticket->api_token = generateApiToken();
        }

        // call hooks
        Hooks::call($ticket, 'saving');
    }

    /**
     * Listen to the Ticket saved event.
     *
     * @param Ticket $ticket
     *
     * @return void
     */
    public function saved(Ticket $ticket)
    {

        // create the ash tokens after the ticket is saved
        $salt = env('APP_KEY');
        if (empty($ticket->ash_token_ip)) {
            $token = md5($salt.$ticket->id.$ticket->ip.$ticket->ip_contact_reference);
            DB::update('update tickets set ash_token_ip = ? where id = ?', [$token, $ticket->id]);
        }
        if (empty($ticket->ash_token_domain)) {
            $token = md5($salt.$ticket->id.$ticket->domain.$ticket->domain_contact_reference);
            DB::update('update tickets set ash_token_domain = ? where id = ?', [$token, $ticket->id]);
        }

        // call hooks
        Hooks::call($ticket, 'saved');
    }
}
