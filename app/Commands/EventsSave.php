<?php

namespace AbuseIO\Commands;

use AbuseIO\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Event;
use AbuseIO\Commands\FindContact;
use AbuseIO\Models\Evidence;
use ICF;
use DB;
use Lang;

class EventsSave extends Command implements SelfHandling
{

    public $events;
    public $evidenceID;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($events, $evidenceID)
    {

        $this->events       = $events;

        $this->evidenceID   = $evidenceID;

    }

    /**
     * Execute the command.
     *
     * @return array
     */
    public function handle()
    {

        // Start with building a classification lookup table
        $classNames = [ ];
        foreach ( Lang::get('classifications') as $classID => $class) {
            $classNames[$class['name']] = $classID;
        }

        // Also build a types lookup table
        $typeNames = [ ];
        foreach ( Lang::get('types.type') as $typeID => $type) {
            $typeNames[$type['name']] = $typeID;
        }

        // Also build a status lookup table
        $statusNames = [ ];
        foreach ( Lang::get('types.status') as $statusID => $status) {
            $statusNames[$status['name']] = $statusID;
        }

        foreach ($this->events as $event) {

            /* Here we will thru all the events and look if these is an existing ticket. We will split them up into
             * two seperate arrays: $eventsNew and $events$known. We can save all the known events in the DB with
             * a single event saving loads of queries
             *
             * IP Owner is leading, as in most cases even if the domain is moved
             * The server might still have a problem. Next to the fact that domains
             * Arent transferred to a new owner 'internally' anyways.
             *
             * So we do a lookup based on the IP same as with the 3.x engine. After
             * the lookup we check wither the domain contact was changed, if so we UPDATE
             * the ticket and put a note somewhere about it. This way the IP owner does
             * not get a new ticket on this matter and the new domain owner is getting updates.
             *
             * As the ASH link is based on the contact code, the old domain owner will not
             * have any access to the ticket anymore.
             */

            // Lookup the ip contact and if needed the domain contact too

            $ipContact = FindContact::byIP($event['ip']);

            if ($event['domain'] != '') {
                $domainContact = FindContact::byDomain($event['domain']);
            }

            // Search to see if there is an existing ticket for this event classification
            // Todo: somehow add the domain too!!!!

            $search = Ticket::
                  where('ip', '=', $event['ip'])
                ->where('class_id', '=', $classNames[$event['class']], 'AND')
                ->where('type_id', '=', $typeNames[$event['type']], 'AND')
                ->where('ip_contact_reference', '=', $ipContact->reference, 'AND')
                ->where('status_id', '!=', 2, 'AND')
                ->get();

            if ($search->count() === 0) {

                // Build an array with all new tickes and save it with its related event and evidence link.

                $newTicket = new Ticket;

                $newTicket->ip                         = $event['ip'];
                $newTicket->domain                     = $event['domain'];
                $newTicket->class_id                   = $classNames[$event['class']];
                $newTicket->type_id                    = $typeNames[$event['type']];
                $newTicket->ip_contact_reference       = $ipContact->reference;
                $newTicket->ip_contact_name            = $ipContact->name;
                $newTicket->ip_contact_email           = $ipContact->email;
                $newTicket->ip_contact_rpchost         = $ipContact->rpc_host;
                $newTicket->ip_contact_rpckey          = $ipContact->rpc_key;
                $newTicket->ip_contact_auto_notify     = $ipContact->auto_notify;
                
                if ($event['domain'] != '') {
                    $newTicket->domain_contact_reference   = $domainContact->reference;
                    $newTicket->domain_contact_name        = $domainContact->name;
                    $newTicket->domain_contact_email       = $domainContact->email;
                    $newTicket->domain_contact_rpchost     = $domainContact->rpc_host;
                    $newTicket->domain_contact_rpckey      = $domainContact->rpc_key;
                    $newTicket->domain_contact_auto_notify = $domainContact->auto_notify;
                }
                
                $newTicket->status_id                  = 1;
                $newTicket->notified_count             = 0;
                $newTicket->last_notify_count          = 0;
                $newTicket->last_notify_timestamp      = 0;

                $newTicket->save();


                $newEvent  = new Event;

                $newEvent->evidence_id = $this->evidenceID;
                $newEvent->information = $event['information'];
                $newEvent->source      = $event['source'];
                $newEvent->ticket_id   = $newTicket->id;
                $newEvent->timestamp   = $event['timestamp'];

                $newEvent->save();

                // Call notifier action handler, type new

            } elseif ($search->count() === 1) {

                $ticketID = $search[0]->id;

                if (Event::
                      where('information', '=', $event['information'])
                    ->where('source', '=', $event['source'])
                    ->where('ticket_id', '=', $ticketID)
                    ->where('timestamp', '=', $event['timestamp'])
                    ->exists()
                ) {

                    // Exact duplicate match so we will ignore this event

                } else {

                    // New unique event, so we will save this

                    $newEvent = new Event;

                    $newEvent->evidence_id = $this->evidenceID;
                    $newEvent->information = $event['information'];
                    $newEvent->source = $event['source'];
                    $newEvent->ticket_id = $ticketID;
                    $newEvent->timestamp = $event['timestamp'];

                    $newEvent->save();

                    // Call notifier action handler, type update
                }

                // This is an existing ticket

            } else $this->failed('Unable to link to ticket, multiple open tickets found for same event type');

        }

        $this->success('');

    }

}
