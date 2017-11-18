<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Ticket;

/**
 * Class TicketUpdate.
 */
class TicketUpdate extends Job
{
    /**
     * Update domain contact for a ticket.
     *
     * @param Ticket $ticket
     */
    public static function domainContact($ticket)
    {
        $domainContact = FindContact::byDomain($ticket['domain']);

        // Update Domain Contact fields in ticket
        $ticket->domain_contact_account_id = $domainContact->account_id;
        $ticket->domain_contact_reference = $domainContact->reference;
        $ticket->domain_contact_name = $domainContact->name;
        $ticket->domain_contact_email = $domainContact->email;
        $ticket->domain_contact_api_host = $domainContact->api_host;
        $ticket->domain_contact_auto_notify = $domainContact->auto_notify();

        // Clear the ash_token, so it will be generated again
        $ticket->ash_token_domain = '';
        $ticket->save();
    }

    /**
     * Update IP contact for a ticket.
     *
     * @param Ticket $ticket
     */
    public static function ipContact($ticket)
    {
        $ipContact = FindContact::byIP($ticket['ip']);

        // Update IP Contact fields in ticket
        $ticket->ip_contact_account_id = $ipContact->account_id;
        $ticket->ip_contact_reference = $ipContact->reference;
        $ticket->ip_contact_name = $ipContact->name;
        $ticket->ip_contact_email = $ipContact->email;
        $ticket->ip_contact_api_host = $ipContact->api_host;
        $ticket->ip_contact_auto_notify = $ipContact->auto_notify();

        // Clear the ash_token, so it will be generated again
        $ticket->ash_token_ip = '';
        $ticket->save();
    }

    /**
     * Call to update contact with optional value if its a single contact.
     *
     * @param Ticket $ticket
     * @param string $only
     */
    public static function contact($ticket, $only)
    {
        // If an invalid value for $only is given, set default (null = both)
        if (!in_array($only, ['ip', 'domain'])) {
            $only = null;
        }

        switch ($only) {
            case 'ip':
                static::ipContact($ticket);
                break;
            case 'domain':
                static::domainContact($ticket);
                break;
            default:
                static::ipContact($ticket);
                static::domainContact($ticket);
        }
    }

    /**
     * @param Ticket   $ticket
     * @param int|null $newstatus
     *
     * @return bool
     */
    public static function status($ticket, $newstatus = null)
    {

        // convert to uppercase
        $newstatus = strtoupper($newstatus);

        //status list is defined in config/types.php
        if (array_key_exists($newstatus, config('types.status.abusedesk'))) {
            $ticket->status_id = $newstatus;
            $ticket->save();

            return true;
        } else {
            return false;
        }
    }
}
