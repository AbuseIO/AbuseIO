<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Ticket;

/**
 * Class TicketUpdate
 * @package AbuseIO\Jobs
 */
class TicketUpdate extends Job
{
    /**
     * Update domain contact for a ticket
     *
     * @param Ticket $ticket
     */
    public static function domainContact($ticket)
    {
        $domainContact = FindContact::byDomain($ticket['domain']);

        // Update Domain Contact fields in ticket
        $ticket->domain_contact_account_id  = $domainContact->account_id;
        $ticket->domain_contact_reference   = $domainContact->reference;
        $ticket->domain_contact_name        = $domainContact->name;
        $ticket->domain_contact_email       = $domainContact->email;
        $ticket->domain_contact_api_host    = $domainContact->api_host;
        $ticket->domain_contact_api_key     = $domainContact->api_key;
        $ticket->domain_contact_auto_notify = $domainContact->auto_notify;
        $ticket->save();
    }

    /**
     * Update IP contact for a ticket
     *
     * @param Ticket $ticket
     */
    public static function ipContact($ticket)
    {
        $ipContact = FindContact::byIP($ticket['ip']);

        // Update IP Contact fields in ticket
        $ticket->ip_contact_account_id      = $ipContact->account_id;
        $ticket->ip_contact_reference       = $ipContact->reference;
        $ticket->ip_contact_name            = $ipContact->name;
        $ticket->ip_contact_email           = $ipContact->email;
        $ticket->ip_contact_api_host        = $ipContact->api_host;
        $ticket->ip_contact_api_key         = $ipContact->api_key;
        $ticket->ip_contact_auto_notify     = $ipContact->auto_notify;
        $ticket->save();
    }

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

    public static function status($ticket, $newstatus = null)
    {
        /**
         * status list is defined in resources/lang/<lang>/types.php
         * 1: Open
         * 2: Closed
         * 3: Escalated
         * 4: Ignored
         * 5: Resolved
         */

        $statuslist = [
            'open'      => 1,
            'closed'    => 2,
            'escalated' => 3,
            'ignored'   => 4,
            'resolved'  => 5,
        ];

        if (array_key_exists($newstatus, $statuslist)) {
            $ticket->status_id = $statuslist[$newstatus];
            $ticket->save();
        } else {
            echo "wrong status given.";
        }
    }
}
