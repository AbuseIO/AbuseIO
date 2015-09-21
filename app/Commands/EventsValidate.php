<?php

namespace AbuseIO\Commands;

use AbuseIO\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Validator;
use Lang;
use Log;

class EventsValidate extends Command implements SelfHandling
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
            /**
             *  TODO - implement a lot of validation here
             *  TODO - Requires some extending validation rules
             *  http://laravel.com/docs/4.2/validation#custom-validation-rules
             */

            // Check valid IP
            $validator = Validator::make(
                [
                    'ip' => $event['ip'],
                ],
                [
                    'ip' => 'required|ip',
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

            // check valid domain name
            if ($event['domain'] !== false) {
            }

            // check valid URI
            if ($event['uri'] !== false) {
            }

            // check valid Type
            $validType = false;
            foreach (Lang::get('types.type') as $typeID => $type) {
                if ($type['name'] == $event['type']) {
                    $validType = true;
                }
            }
            if ($validType !== true) {
                return $this->failed('Invalid type used');
            }

            // Check valid Class
            $validClass = false;
            foreach (Lang::get('classifications') as $classID => $class) {
                if ($class['name'] == $event['class']) {
                    $validClass = true;
                }
            }
            if ($validClass !== true) {
                return $this->failed('Invalid classification used');
            }

            // Check if information is json encoded

            // This any failed, we call a $this->failed($message)

        }
        return $this->success('');
    }
}
