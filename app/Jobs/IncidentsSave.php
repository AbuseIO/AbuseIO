<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Event;
use AbuseIO\Models\Ticket;
use Log;
use Validator;

/**
 * This incidentsSave class handles the actual writing of incidents to the database.
 *
 * Class incidentsSave
 */
class IncidentsSave extends Job
{
    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the command.
     *
     * @param array $incidents
     * @param int   $evidenceID
     *
     * @return array
     */
    public function save($incidents, $evidenceID)
    {
        $ticketCount = 0;
        $incidentCount = 0;
        $incidentsIgnored = 0;

        foreach ($incidents as $incident) {
            /* Here we will seek through all the incidents and look if there is an existing ticket. We will split
             * them up into two separate arrays: $incidentsNew and $incidents$known. We can save all the known
             * incidents in the DB with a single incident saving loads of queries
             *
             * IP Owner is leading, as in most cases even if the domain is moved
             * The server might still have a problem. Next to the fact that domains
             * Aren't transferred to a new owner 'internally' anyways.
             *
             * So we do a lookup based on the IP same as with the 3.x engine. After
             * the lookup we check wither the domain contact was changed, if so we UPDATE
             * the ticket and put a note somewhere about it. This way the IP owner does
             * not get a new ticket on this matter and the new domain owner is getting updates.
             *
             * As the ASH link is based on the contact code, the old domain owner will not
             * have any access to the ticket anymore.
             */

            // If an incident is too old we are ignoring it
            if (config('main.reports.min_lastseen') !== false &&
                strtotime(config('main.reports.min_lastseen')) !== false &&
                strtotime(config('main.reports.min_lastseen').' ago') > $incident->timestamp
            ) {
                Log::debug(
                    get_class($this).': '.
                    'is ignoring incident because its older then '.config('main.reports.min_lastseen')
                );

                continue;
            }

            // Lookup the ip contact and if needed the domain contact too
            $findContact = new FindContact();

            $ipContact = $findContact->byIP($incident->ip);

            if ($incident->domain != '') {
                $domainContact = $findContact->byDomain($incident->domain);
            } else {
                $domainContact = $findContact->undefined();
            }

            /*
             * Ignore the incident if both ip and domain contacts are undefined and the resolving of an contact
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
                        get_class($this).': '.
                        'is ignoring incident because there is no IP or Domain contact'
                    );

                    continue;
                }
            }

            /*
             * Search to see if there is an existing ticket for this incident classification
             */
            $ticket = Ticket::where('ip', '=', $incident->ip)
                ->where(
                    'domain',
                    '=',
                    empty($incident->domain) ? '' : $incident->domain,
                    'AND'
                )
                ->where('class_id', '=', $incident->class, 'AND')
                ->where('ip_contact_reference', '=', $ipContact->reference, 'AND')
                ->where('status_id', '!=', 'CLOSED', 'AND')
                ->get();

            if ($ticket->count() === 0) {
                /*
                 * If there are no search results then there is no existing ticket and we should create one
                 */
                $ticketCount++;

                $newTicket = new Ticket();
                $newTicket->ip = $incident->ip;
                $newTicket->domain = empty($incident->domain) ? '' : $incident->domain;
                $newTicket->class_id = $incident->class;
                $newTicket->type_id = $incident->type;

                $newTicket->ip_contact_account_id = $ipContact->account_id;
                $newTicket->ip_contact_reference = $ipContact->reference;
                $newTicket->ip_contact_name = $ipContact->name;
                $newTicket->ip_contact_email = $ipContact->email;
                $newTicket->ip_contact_api_host = $ipContact->api_host;
                $newTicket->ip_contact_auto_notify = $ipContact->auto_notify();
                $newTicket->ip_contact_notified_count = 0;

                $newTicket->domain_contact_account_id = $domainContact->account_id;
                $newTicket->domain_contact_reference = $domainContact->reference;
                $newTicket->domain_contact_name = $domainContact->name;
                $newTicket->domain_contact_email = $domainContact->email;
                $newTicket->domain_contact_api_host = $domainContact->api_host;
                $newTicket->domain_contact_auto_notify = $domainContact->auto_notify();
                $newTicket->domain_contact_notified_count = 0;

                $newTicket->status_id = 'OPEN';
                $newTicket->last_notify_count = 0;
                $newTicket->last_notify_timestamp = 0;

                if (property_exists($incident, 'remote_api_token')) {
                    $newTicket->remote_api_token = $incident->remote_api_token;
                }
                if (property_exists($incident, 'remote_api_url')) {
                    $newTicket->remote_api_url = $incident->remote_api_url;
                }
                if (property_exists($incident, 'remote_ticket_id')) {
                    $newTicket->remote_ticket_id = $incident->remote_ticket_id;
                }
                if (property_exists($incident, 'remote_ash_link')) {
                    $newTicket->remote_ash_link = $incident->remote_ash_link;
                }

                // Validate the model before saving
                $validator = Validator::make(
                    json_decode(json_encode($newTicket), true),
                    Ticket::createRules()
                );
                if ($validator->fails()) {
                    return $this->error(
                        'DevError: Internal validation failed when saving the Ticket object '.
                        implode(' ', $validator->messages()->all())
                    );
                }

                $newTicket->save();

                $newEvent = new Event();
                $newEvent->evidence_id = $evidenceID;
                $newEvent->information = $incident->information;
                $newEvent->source = $incident->source;
                $newEvent->ticket_id = $newTicket->id;
                $newEvent->timestamp = $incident->timestamp;

                // Validate the model before saving
                $validator = Validator::make(
                    json_decode(json_encode($newEvent), true),
                    Event::createRules()
                );
                if ($validator->fails()) {
                    return $this->error(
                        'DevError: Internal validation failed when saving the Event object '.
                        implode(' ', $validator->messages()->all())
                    );
                }

                $newEvent->save();
            } elseif ($ticket->count() === 1) {
                /*
                 * There is an existing ticket, so we just need to add the incident to this ticket. If the
                 * incident is an exact match we consider it a duplicate and will ignore it.
                 */
                $ticket = $ticket[0];

                if (Event::where('information', '=', $incident->information)
                        ->where('source', '=', $incident->source)
                        ->where('ticket_id', '=', $ticket->id)
                        ->where('timestamp', '=', $incident->timestamp)
                        ->exists()
                ) {
                    $incidentsIgnored++;
                } else {
                    // New unique incident, so we will save this
                    $incidentCount++;

                    $newEvent = new Event();
                    $newEvent->evidence_id = $evidenceID;
                    $newEvent->information = $incident->information;
                    $newEvent->source = $incident->source;
                    $newEvent->ticket_id = $ticket->id;
                    $newEvent->timestamp = $incident->timestamp;

                    // Validate the model before saving
                    $validator = Validator::make(
                        json_decode(json_encode($newEvent), true),
                        Event::createRules()
                    );
                    if ($validator->fails()) {
                        return $this->error(
                            'DevError: Internal validation failed when saving the Event object '.
                            implode(' ', $validator->messages()->all())
                        );
                    }

                    $newEvent->save();

                    /*
                     * If the reference has changed for the domain owner, then we update the ticket with the new
                     * domain owner. We not check if anything else then the reference has changed. If you change the
                     * contact data you have the option to propegate it onto open tickets.
                     */
                    if (!empty($incident->domain) &&
                        $domainContact !== false &&
                        $domainContact->reference !== $ticket->domain_contact_reference
                    ) {
                        $ticket->domain_contact_reference = $domainContact->reference;
                        $ticket->domain_contact_name = $domainContact->name;
                        $ticket->domain_contact_email = $domainContact->email;
                        $ticket->domain_contact_api_host = $domainContact->api_host;
                        $ticket->domain_contact_auto_notify = $domainContact->auto_notify();
                        $ticket->accountDomain()->associate($domainContact->account);
                    }

                    /*
                     * Upgrade the type if the received event has a higher priority type included
                     */
                    $priority = ['INFO', 'ABUSE', 'ESCALATION'];
                    if (array_search($ticket->type_id, $priority) < array_search($incident->type, $priority)) {
                        $ticket->type_id = $incident->type;
                    }

                    /*
                     * If the ticket was set to resolved, move it back to open
                     */
                    if ($ticket->status_id == 'RESOLVED') {
                        $ticket->status_id = 'OPEN';
                    }

                    /*
                     * Walk through the escalation upgrade path, and upgrade if required
                     */
                    //echo config("escalations.{$ticket->class_id}.abuse.enabled");
                    if (is_array(config("escalations.{$ticket->class_id}"))) {
                        // There is a specific escalation path for this class
                        $escalationPath = $ticket->class_id;
                    } else {
                        // Use the default escalation path
                        $escalationPath = 'DEFAULT';
                    }

                    // Check if all the values are set, or log a warning that were skipping escalation paths
                    if (!is_bool(empty(config("escalations.{$escalationPath}.abuse.enabled"))) ||
                        !is_numeric(config("escalations.{$escalationPath}.abuse.threshold")) ||
                        !is_bool(empty(config("escalations.{$escalationPath}.escalation.enabled"))) ||
                        !is_numeric(config("escalations.{$escalationPath}.escalation.threshold"))
                    ) {
                        Log::warning(
                            get_class($this).': '.
                            'Escalation path settings are missing or incomplete. Skipping this phase'
                        );
                    } else {

                        // Now actually check if anything needs to be changed and if so, change it.
                        if (!empty(config("escalations.{$escalationPath}.abuse.enabled")) &&
                            !empty(config("escalations.{$escalationPath}.abuse.threshold")) &&
                            $ticket->events->count() > config("escalations.{$escalationPath}.abuse.threshold") &&
                            $ticket->type_id == 'INFO'
                        ) {
                            // Upgrade to abuse
                            Log::debug(
                                get_class($this).': '.
                                "An escalation path threshold has been reached for ticket {$ticket->id}, ".
                                'threshold: '.config("escalations.{$escalationPath}.abuse.threshold").', '.
                                'setting: info -> abuse'
                            );
                            $ticket->type_id = 'ABUSE';
                        }

                        if (!empty(config("escalations.{$escalationPath}.escalation.enabled")) &&
                            !empty(config("escalations.{$escalationPath}.escalation.threshold")) &&
                            $ticket->events->count() > config("escalations.{$escalationPath}.escalation.threshold") &&
                            $ticket->type_id == 'ABUSE'
                        ) {
                            // Upgrade to escalation
                            Log::debug(
                                get_class($this).': '.
                                "An escalation path threshold has been reached for ticket {$ticket->id}, ".
                                'threshold: '.config("escalations.{$escalationPath}.escalation.threshold").', '.
                                'setting: abuse -> escalation'
                            );
                            $ticket->type_id = 'ESCALATION';
                        }
                    }

                    // Validate the model before saving
                    $validator = Validator::make(
                        json_decode(json_encode($ticket), true),
                        Ticket::createRules()
                    );
                    if ($validator->fails()) {
                        return $this->error(
                            'DevError: Internal validation failed when saving the Ticket object '.
                            implode(' ', $validator->messages()->all())
                        );
                    }

                    $ticket->save();
                }
            } else {
                /*
                 * We should not never have more then two open tickets for the same case. If this happens there is a
                 * fault in the aggregator which must be resolved first. Until then we will permfail here.
                 */
                return $this->error('Unable to link to ticket, multiple open tickets found for same incident type');
            }
        }

        Log::debug(
            get_class($this).': '.
            "has completed creating {$ticketCount} new tickets, ".
            "linking {$incidentCount} new incidents and ignored $incidentsIgnored duplicates"
        );

        return $this->success('');
    }
}
