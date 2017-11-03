<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\Ticket;
use League\Fractal\TransformerAbstract;

class TicketTransformer extends TransformerAbstract
{
    /**
     * converts the ticket object to a generic array.
     *
     * @param Ticket $ticket
     *
     * @return array
     */
    public function transform(Ticket $ticket)
    {
        // transform the events and notes
        $events = [];
        $notes = [];

        foreach ($ticket->events as $event) {
            $events[] = (new EventTransformer())->transform($event);
        }

        foreach ($ticket->notes as $note) {
            $notes[] = (new NoteTransformer())->transform($note);
        }

        return [
            'id'                            => (int) $ticket->id,
            'ip'                            => (string) $ticket->ip,
            'domain'                        => (string) $ticket->domain,
            'class_id'                      => (string) $ticket->class_id,
            'type_id'                       => (string) $ticket->type_id,
            'ip_contact_account_id'         => (int) $ticket->ip_contact_account_id,
            'ip_contact_reference'          => (string) $ticket->ip_contact_reference,
            'ip_contact_name'               => (string) $ticket->ip_contact_name,
            'ip_contact_email'              => (string) $ticket->ip_contact_email,
            'ip_contact_api_host'           => (string) $ticket->ip_contact_api_host,
            'ip_contact_auto_notify'        => (bool) $ticket->ip_contact_auto_notify,
            'ip_contact_notified_count'     => (int) $ticket->ip_contact_notified_count,
            'domain_contact_account_id'     => (int) $ticket->domain_contact_account_id,
            'domain_contact_reference'      => (string) $ticket->domain_contact_reference,
            'domain_contact_name'           => (string) $ticket->domain_contact_name,
            'domain_contact_email'          => (string) $ticket->domain_contact_email,
            'domain_contact_api_host'       => (string) $ticket->domain_contact_api_host,
            'domain_contact_auto_notify'    => (bool) $ticket->domain_contact_auto_notify,
            'domain_contact_notified_count' => (int) $ticket->domain_contact_notified_count,
            'status_id'                     => (string) $ticket->status_id,
            'contact_status_id'             => (string) $ticket->contact_status_id,
            'last_notify_count'             => (int) $ticket->last_notify_count,
            'last_notify_timestamp'         => (int) $ticket->last_notify_timestamp,
            'event_count'                   => (int) $ticket->event_count,
            'note_count'                    => (int) $ticket->note_count,
            'ash_token_ip'                  => (string) $ticket->ash_token_ip,
            'ash_token_domain'              => (string) $ticket->ash_token_domain,
            'remote_api_token'              => (string) $ticket->remote_api_token,
            'api_token'                     => (string) $ticket->api_token,
            'remote_api_url'                => (string) $ticket->remote_api_url,
            'remote_ticket_id'              => (string) $ticket->remote_ticket_id,
            'remote_ash_link'               => (string) $ticket->remote_ash_link,
            'events'                        => $events,
            'notes'                         => $notes,
        ];
    }
}
