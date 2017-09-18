<?php

namespace AbuseIO\Observers;

use AbuseIO\Hook\Common as Hooks;
use AbuseIO\Jobs\Notification;
use AbuseIO\Models\Event;
use AbuseIO\Models\Ticket;

class EventObserver
{
    /**
     *  Listen for the created Event event.
     *
     * @param Event $event
     */
    public function created(Event $event)
    {
        // call notifications if needed
        $ticket = Ticket::find($event->ticket_id);

        if ($ticket) {
            // defensive programming

            if ($ticket->ip_contact_auto_notify && $ticket->domain_contact_auto_notify) {
                // send to both contacts
                $notification = new Notification();
                $notification->walkList(
                    $notification->buildList($ticket->id)
                );
            } else {

                // only ip contact
                if ($ticket->ip_contact_auto_notify) {
                    $notification = new Notification();
                    $notification->walkList(
                        $notification->buildList($ticket->id, false, false, 'ip')
                    );
                }

                // only domain contact
                if ($ticket->ip_contact_auto_notify) {
                    $notification = new Notification();
                    $notification->walkList(
                        $notification->buildList($ticket->id, false, false, 'domain')
                    );
                }
            }
        }

        // call hooks
        Hooks::call($event, 'created');
    }

    /**
     * Listen to the Event saving event.
     *
     * @param Event $event
     */
    public function saving(Event $event)
    {
        // call hooks
        Hooks::call($event, 'saving');
    }

    /**
     * Listen to the Event saved event.
     *
     * @param Event $event
     */
    public function saved(Event $event)
    {
        // call hooks
        Hooks::call($event, 'saved');
    }
}
