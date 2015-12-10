<?php

namespace AbuseIO\Jobs;

use AbuseIO\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Event;
use AbuseIO\Jobs\FindContact;
use AbuseIO\Models\Evidence;
use Lang;
use Log;

class EventsSave extends Job implements SelfHandling
{
    public $events;
    public $evidenceID;

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct($events, $evidenceID)
    {
        $this->events       = $events;
        $this->evidenceID   = $evidenceID;
    }

    /**
     * Execute the command.
     * @return array
     */
    public function handle()
    {
        $ticketCount = 0;
        $eventCount = 0;
        $eventsIgnored = 0;

        foreach ($this->events as $event) {
            /* Here we will seek through all the events and look if there is an existing ticket. We will split them up
             * into two seperate arrays: $eventsNew and $events$known. We can save all the known events in the DB with
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

            // If an event is too old we are ignoring it
            if (config('main.reports.min_lastseen') !== false &&
                strtotime(config('main.reports.min_lastseen')) !== false &&
                strtotime(config('main.reports.min_lastseen') . ' ago') < $event['timestamp']
            ) {
                Log::debug(
                    '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                    "is ignoring event because its older then" . config('main.reports.min_lastseen')
                );

                continue;
            }

            // Start with building a classification lookup table  and switch out name for ID
            foreach ((array)Lang::get('classifications') as $classID => $class) {
                if ($class['name'] == $event['class']) {
                    $event['class'] = $classID;
                }
            }

            // Also build a types lookup table and switch out name for ID
            foreach ((array)Lang::get('types.type') as $typeID => $type) {
                if ($type['name'] == $event['type']) {
                    $event['type'] = $typeID;
                }
            }

            // Lookup the ip contact and if needed the domain contact too
            $ipContact = FindContact::byIP($event['ip']);

            if ($event['domain'] != '') {
                $domainContact = FindContact::byDomain($event['domain']);
            } else {
                $domainContact = FindContact::undefined();
            }

            /*
             * Ignore the event if both ip and domain contacts are undefined and the resolving of an contact
             * was required. This is handy to ignore any reports that are not considered local, but use
             * with caution as it might just ignore anything if your IP/domains are not correctly configured
             */
            if ($ipContact->reference == 'UNDEF' &&
                $domainContact->reference == 'UNDEF' &&
                config('main.reports.resolvable_only') === true
            ) {
                if ((!empty($domainContact) && $domainContact->reference == 'UNDEF') ||
                    empty($domainContact)
                ) {
                    Log::debug(
                        '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                        "is ignoring event because there is no IP or Domain contact"
                    );

                    continue;
                }
            }

            /*
             * Search to see if there is an existing ticket for this event classification
             */
            $ticket = Ticket::where('ip', '=', $event['ip'])
                ->where('class_id', '=', $event['class'], 'AND')
                ->where('type_id', '=', $event['type'], 'AND')
                ->where('ip_contact_reference', '=', $ipContact->reference, 'AND')
                ->where('status_id', '!=', 2, 'AND')
                ->get();

            if ($ticket->count() === 0) {
                /*
                 * If there are no search results then there is no existing ticket and we should create one
                 */
                $ticketCount++;

                $newTicket = new Ticket();
                $newTicket->ip                         = $event['ip'];
                $newTicket->domain                     = empty($event['domain']) ? '' : $event['domain'];
                $newTicket->class_id                   = $event['class'];
                $newTicket->type_id                    = $event['type'];

                $newTicket->ip_contact_account_id      = $ipContact->account_id;
                $newTicket->ip_contact_reference       = $ipContact->reference;
                $newTicket->ip_contact_name            = $ipContact->name;
                $newTicket->ip_contact_email           = $ipContact->email;
                $newTicket->ip_contact_api_host        = $ipContact->api_host;
                $newTicket->ip_contact_api_key         = $ipContact->api_key;
                $newTicket->ip_contact_auto_notify     = $ipContact->auto_notify;

                $newTicket->domain_contact_account_id  = $domainContact->account_id;
                $newTicket->domain_contact_reference   = $domainContact->reference;
                $newTicket->domain_contact_name        = $domainContact->name;
                $newTicket->domain_contact_email       = $domainContact->email;
                $newTicket->domain_contact_api_host    = $domainContact->api_host;
                $newTicket->domain_contact_api_key     = $domainContact->api_key;
                $newTicket->domain_contact_auto_notify = $domainContact->auto_notify;

                $newTicket->status_id               = 1;
                $newTicket->notified_count          = 0;
                $newTicket->last_notify_count       = 0;
                $newTicket->last_notify_timestamp   = 0;

                $newTicket->save();

                $newEvent = new Event();
                $newEvent->evidence_id = $this->evidenceID;
                $newEvent->information = $event['information'];
                $newEvent->source      = $event['source'];
                $newEvent->ticket_id   = $newTicket->id;
                $newEvent->timestamp   = $event['timestamp'];

                $newEvent->save();

            } elseif ($ticket->count() === 1) {
                /*
                 * There is an existing ticket, so we just need to add the event to this ticket. If the event is an
                 * exact match we consider it a duplicate and will ignore it.
                 */
                $ticket = $ticket[0];

                if (Event::where('information', '=', $event['information'])
                        ->where('source', '=', $event['source'])
                        ->where('ticket_id', '=', $ticket->id)
                        ->where('timestamp', '=', $event['timestamp'])
                        ->exists()
                ) {
                    $eventsIgnored++;

                } else {
                    // New unique event, so we will save this
                    $eventCount++;

                    $newEvent = new Event();

                    $newEvent->evidence_id  = $this->evidenceID;
                    $newEvent->information  = $event['information'];
                    $newEvent->source       = $event['source'];
                    $newEvent->ticket_id    = $ticket->id;
                    $newEvent->timestamp    = $event['timestamp'];

                    $newEvent->save();

                    /*
                     * If the reference has changed for the domain owner, then we update the ticket with the new
                     * domain owner. We not check if anything else then the reference has changed. If you change the
                     * contact data you have the option to propogate it onto open tickets.
                     */
                    if (!empty($event['domain']) &&
                        $domainContact !== false &&
                        $domainContact->reference !== $ticket->domain_contact_reference
                    ) {
                        $ticket->domain_contact_reference   = $domainContact->reference;
                        $ticket->domain_contact_name        = $domainContact->name;
                        $ticket->domain_contact_email       = $domainContact->email;
                        $ticket->domain_contact_api_host    = $domainContact->api_host;
                        $ticket->domain_contact_api_key     = $domainContact->api_key;
                        $ticket->domain_contact_auto_notify = $domainContact->auto_notify;
                        $ticket->account_id                 = $domainContact->account->id;

                        $ticket->save();
                    }

                    // TODO: If this is an abuse/escalation ticket and currently 'resolved' then put status back to Open

                    // TODO: Implement escalation triggers

                }

            } else {
                /*
                 * We should not never have more then two open tickets for the same case. If this happens there is a
                 * fault in the aggregator which must be resolved first. Until then we will permfail here.
                 */
                $this->failed('Unable to link to ticket, multiple open tickets found for same event type');
            }
        }

        Log::debug(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            "has completed creating {$ticketCount} new tickets, " .
            "linking {$eventCount} new events and ignored $eventsIgnored duplicates"
        );

        $this->success('');
    }
}
