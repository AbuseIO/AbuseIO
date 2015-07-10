<?php

namespace AbuseIO\Commands;

use AbuseIO\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Validator;
use Lang;

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
        // Start with building a classification lookup table
        $classNames = [ ];
        foreach (Lang::get('classifications') as $classID => $class) {
            $classNames[$class['name']] = $classID;
        }

        // Also build a types lookup table
        $typeNames = [ ];
        foreach (Lang::get('types.type') as $typeID => $type) {
            $typeNames[$type['name']] = $typeID;
        }

        foreach ($this->events as $event) {

            // TODO - implement a lot of validation here

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

            // check valid URI

            // check valid Type and switch it out for the ID
            if (!isset($typeNames[$event['type']])) {
                return $this->failed('Parser used a non existing type: ' . $event['type']);
            } else {
                $event['type'] = $typeNames[$event['type']];
            }

            // Check valid Class and switch it out for the ID
            if (!isset($classNames[$event['class']])) {
                return $this->failed('Parser used a non existing classification: ' . $event['class']);
            } else {
                $event['class'] = $classNames[$event['class']];
            }

            // Check if information is json encoded

            // This any failed, we call a $this->failed($message)

        }

        return $this->success('');

    }
}
