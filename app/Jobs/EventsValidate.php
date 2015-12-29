<?php

namespace AbuseIO\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Validator;

/**
 * This EventsValidate class handles validation of multiple events
 *
 * Class EventsValidate
 */
class EventsValidate extends Job implements SelfHandling
{
    public $events;

    /**
     * Create a new command instance.
     *

     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the command.
     *
     * @return array
     * @param array $events
     */
    public function check($events)
    {
        if (empty($events)) {
            return $this->failed("Empty resultset cannot be validated");
        }

        foreach ($events as $event) {

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
