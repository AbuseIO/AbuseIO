<?php

namespace AbuseIO\Commands;

use AbuseIO\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use AbuseIO\Models\Ticket;
use AbuseIO\Commands\FindCustomer;
use AbuseIO\Models\Evidence;
use ICF;
use DB;

class EventsSave extends Command implements SelfHandling
{

    public $events;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($events)
    {

        $this->events = $events;

    }

    /**
     * Execute the command.
     *
     * @return array
     */
    public function handle()
    {

        $newTickets = [ ];
        /* setup as follows:
         *
         * $newTickets =
         * [
         * 'id' =>
         *     [
         *     'ticket' = array(ticket)
         *     'events' = array('id' => array(event))
         *     'evidence' = array('id' => array(evidence))
         *     ]
         * ];
         *
         */

        $existingTickets = [ ];
        /*
         * setup as follows:
         *
         * $existingTickets =
         * [
         * 'id' =>
         *     [
         *     'ticket_id' = int
         *     'events' = array('id' => array(event))
         *     'evidence' = array('id' => array(evidence))
         *     ]
         * ];
         *
         */

        foreach ($this->events as $event) {

            // Here we will thru all the events and look if these is an existing ticket. We will split them up into
            // two seperate arrays: $eventsNew and $events$known. We can save all the known events in the DB with
            // a single event saving loads of queries.

            /*
                'ip',
                'domain',
                'class_id',
                'type_id',
                'ip_contact_reference',
                'ip_contact_name',
                'ip_contact_email',
                'ip_contact_rpchost',
                'ip_contact_rpckey',
                'domain_contact_reference',
                'domain_contact_name',
                'domain_contact_email',
                'domain_contact_rpchost',
                'domain_contact_rpckey',
                'status_id',
                'auto_notify',
                'notified_count',
                'last_notify_count',
                'last_notify_timestamp'
             */

            $ipContact = FindCustomer::byIP($event['ip']);

            if ($event['domain'] != '') {
                $domainContact = FindCustomer::byDomain($event['domain']);
            }

            $search = Ticket::
                  where('ip', '=', $event['ip'])
                ->where('class_id', '=', $event['class'], 'AND')
                ->where('type_id', '=', $event['type'], 'AND')
                ->where('ip_contact_reference', '=', $ipContact->reference, 'AND')
                ->where('status_id', '!=', 2, 'AND')
                ->get();


            if ($search->count() === 0) {

                // Build an array with all new tickes and save it with its related event and evidence link.

            } elseif ($search->count() === 1) {

                // This is an existing ticket

            } else $this->failed('Unable to link to ticket, multiple open tickets found for same event type');

        }

        $this->success('');

    }

}
