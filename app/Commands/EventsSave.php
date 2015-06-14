<?php

namespace AbuseIO\Commands;

use AbuseIO\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;

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

        foreach ($this->events as $event) {

            // Here we will thru all the events and look if these is an existing ticket. We will split them up into
            // two seperate arrays: $eventsNew and $events$known. We can save all the known events in the DB with
            // a single event saving loads of queries.



        }

        $this->success('');

    }

}
