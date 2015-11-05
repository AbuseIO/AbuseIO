<?php

namespace AbuseIO\Jobs;

use AbuseIO\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class Notification extends Job implements SelfHandling
{
    public $ticket;

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Create is list of tickets that needs notifications.
     * @return array
     */
    public function buidlList($filter)
    {

    }


    /**
     * Sends out notifications based on the configured notification modules.
     * @param  object $ticket
     * @return array
     */
    public function send($ticket)
    {
        if (!empty(config("main.external.notifications"))
            && is_array(config("main.external.notifications"))
        ) {
            foreach (config("main.external.notifications") as $notificationMethod) {

                $class = '\AbuseIO\Notification\\' . $notificationMethod['class'];
                $method = $notificationMethod['method'];

                if (class_exists($class) === true && method_exists($class, $method) === true) {
                    $reflectionMethod = new ReflectionMethod($class, $method);
                    $notification = $reflectionMethod->invoke(new $class, [$ticket, $event, $type]);

                    if ($notification !== true) {
                        Log::error(
                            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                            "Notifications with {$class} did not succeed"
                        );
                    } else {
                        Log::debug(
                            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                            "Notifications with {$class} was successfull"
                        );
                    }
                }
            }
        }

        return false;
    }
}
