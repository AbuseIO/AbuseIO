<?php

namespace AbuseIO\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Log;

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
     * Returns false on failed or havent done anything at all.
     * @param  object $ticket
     * @return array
     */
    public function send($ticket)
    {
        $return = false;

        if (!empty(config("main.external.notifications"))
            && is_array(config("main.external.notifications"))
        ) {
            foreach (config("main.external.notifications") as $notificationMethod) {

                $class = '\AbuseIO\Notification\\' . $notificationMethod['class'];
                $method = $notificationMethod['method'];

                if (class_exists($class) === true && method_exists($class, $method) === true) {
                    $return = true;

                    $reflectionMethod = new ReflectionMethod($class, $method);
                    $notification = $reflectionMethod->invoke(new $class, [ $ticket ]);

                    if ($notification !== true) {
                        Log::error(
                            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                            "Notifications with {$class} did not succeed"
                        );
                        return false;

                    } else {
                        Log::debug(
                            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                            "Notifications with {$class} was successfull"
                        );

                    }
                } else {
                    Log::debug(
                        '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                        "Can not use notification class {$class} because it is not installed " .
                        "or the method {$method} was not available"
                    );

                }
            }
        }

        if ($return === false) {
            Log::warning(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                "None of the notifications configured could be used, check configuration!"
            );
        }
        return $return;
    }
}
