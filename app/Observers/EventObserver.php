<?php

namespace AbuseIO\Observers;

use AbuseIO\Hook\Common as Hooks;
use AbuseIO\Models\Event;

class EventObserver
{

    /**
     *  Listen for the created Event event
     *
     * @param Event $event
     */
    public function created(Event $event)
    {
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
