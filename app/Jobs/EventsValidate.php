<?php

namespace AbuseIO\Jobs;

use AbuseIO\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Validator;
use Lang;

class EventsValidate extends Job implements SelfHandling
{
    public $events;

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct($events)
    {
        $this->events = $events;
    }

    /**
     * Execute the command.
     * @return array
     */
    public function handle()
    {
        if (empty($this->events)) {
            return $this->failed("Empty resultset cannot be validated");
        }

        foreach ($this->events as $event) {

            $validator = Validator::make(
                [
                    'source'        => $event['source'],
                    'ip'            => $event['ip'],
                    'domain'        => $event['domain'],
                    'uri'           => $event['uri'],
                    'class'         => $event['class'],
                    'type'          => $event['type'],
                    'timestamp'     => $event['timestamp'],
                    'information'   => $event['information'],
                ],
                [
                    'source'        => 'required|string',
                    'ip'            => 'required|ip',
                    'domain'        => 'required|stringorboolean|domain',
                    'uri'           => 'required|stringorboolean|uri',
                    'class'         => 'required|string|abuseclass',
                    'type'          => 'required|string|abusetype',
                    'timestamp'     => 'required|int|timestamp',
                    'information'   => 'required|json',
                ]
            );

            if ($validator->fails()) {
                return $this->failed(implode(' ', $validator->messages()->all()));
            }

        }

        return $this->success('');
    }
}
