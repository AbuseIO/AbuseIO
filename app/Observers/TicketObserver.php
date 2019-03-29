<?php

namespace AbuseIO\Observers;

use AbuseIO\Hook\Common as Hooks;
use AbuseIO\Models\Ticket;
use Config;

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

        // create the ash tokens when they don't exist.
        $salt = Config::get('app.key');
        if (empty($ticket->ash_token_ip)) {
            $token = md5($salt.rand().$ticket->ip.$ticket->ip_contact_reference);
            $ticket->ash_token_ip = $token;
        }
        if (empty($ticket->ash_token_domain)) {
            $token = md5($salt.rand().$ticket->domain.$ticket->domain_contact_reference);
            $ticket->ash_token_domain = $token;
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
        // call hooks
        Hooks::call($ticket, 'saved');
    }

    /**
     * Listen to the Ticket updating event.
     *
     * @param Ticket $ticket
     *
     * @return void
     */
    public function updating(Ticket $ticket)
    {
        // call hooks
        Hooks::call($ticket, 'updating');
    }
}
