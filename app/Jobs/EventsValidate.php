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

            /*
             * Common Laravel validations
             */
            $validator = Validator::make(
                [
                    'source' => $event['source'],
                    'ip' => $event['ip'],
                    'domain' => $event['domain'],
                    'uri' => $event['uri'],
                    'class' => $event['class'],
                    'type' => $event['type'],
                    'timestamp' => $event['timestamp'],
                    'information' => $event['information'],
                ],
                [
                    'source' => 'required:string',
                    'ip' => 'required|ip',
                    'domain' => 'required:string',
                    'uri' => 'required:string',
                    'class' => 'required:string',
                    'type' => 'required:string',
                    'timestamp' => 'required:integer',
                    'information' => 'required:json',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->messages();

                $message = '';
                foreach ($messages->all() as $messagePart) {
                    $message .= $messagePart;
                }
                return $this->failed($message);
            }

            /*
             * Manual validations, the ones that laravel does not provide
             * or require some manual tinkering
             */

            // check valid domain name
            if ($event['domain'] !== false) {
                if (!preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $event['domain'])
                    || !preg_match("/^.{1,253}$/", $event['domain'])
                    || !preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $event['domain'])
                ) {
                    return $this->failed('Invalid domain name used:' . $event['domain']);
                }
            }

            // check valid URI
            if ($event['uri'] !== false) {
                if (filter_var(
                    'http://test.for.var.com' . $event['uri'],
                    FILTER_VALIDATE_URL
                ) === false ) {
                    return $this->failed('Invalid URI used' . $event['uri']);
                }
            }

            // check valid timestamp
            if ($event['timestamp'] === false) {
                return $this->failed('Invalid timestamp used');
            }

            // check valid Type
            $validType = false;
            foreach (Lang::get('types.type') as $typeID => $type) {
                if ($type['name'] == $event['type']) {
                    $validType = true;
                }
            }
            if ($validType !== true) {
                return $this->failed("Invalid type used: {$event['type']}");
            }

            // Check valid Class
            $validClass = false;
            foreach (Lang::get('classifications') as $classID => $class) {
                if ($class['name'] == $event['class']) {
                    $validClass = true;
                    break;
                }
            }
            if ($validClass !== true) {
                return $this->failed("Invalid classification used: {$event['class']}");
            }

        }
        return $this->success('');
    }
}
