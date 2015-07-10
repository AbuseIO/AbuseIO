<?php

namespace AbuseIO\Commands;

use AbuseIO\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;

class EventsValidate extends Command implements SelfHandling
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

            // TODO - implement a lot of validation here

            // Check valid source

            // Check valid IP

            // check valid domain name

            // check valid URI

            // check valid Type

            // Check valid Class

            // Check if information is json encoded



            // This any failed, we call a $this->failed($message)

        }

        $this->success('');

    }
}
