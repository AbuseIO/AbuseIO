<?php

namespace AbuseIO\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use ReflectionMethod;
use AbuseIO\Models\Ticket;
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
                    $reflectionMethod = new ReflectionMethod($class, $method);
                    $notification = $reflectionMethod->invoke(new $class, [ $ticket ]);

                    if ($notification !== true) {
                        Log::error(
                            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                            "Notifications with {$class} did not succeed for ticket {$ticket->id}"
                        );

                    } else {
                        $return = true;

                        $ticket->last_notify_count      = $ticket->events->count();
                        $ticket->last_notify_timestamp  = time();
                        $ticket->notified_count         = $ticket->notified_count + 1;
                        //$ticket->save();

                        Log::debug(
                            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                            "Notifications with {$class} was successfull for ticket {$ticket->id}"
                        );

                    }

                } else {
                    Log::warning(
                        '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                        "Notifications with class {$class} cannot be send because it is not installed " .
                        "or the method {$method} was not available"
                    );

                }
            }
        }

        return $return;
    }

    public function walkList($tickets = false)
    {
        Log::info(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            "A notification run has been started"
        );

        if ($tickets === false) {
            $tickets = $this->buildList();
        }

        if (empty($tickets)) {
            Log::info(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                "No tickets that need to send out notifications"
            );
            return true;
        }

        $counter = 0;
        $errors = 0;

        foreach ($tickets as $ticket) {
            $result = $this->send($ticket);

            if (!$result) {
                $errors++;
            } else {
                $counter++;
            }
        }

        Log::debug(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            "Successfully send out {$counter} ticket notifications"
        );

        return $errors;
    }

    /**
     * Create a list of tickets that need outgoing notifications.
     * @return array
     */
    public function buildList($filter = false)
    {
        /*
         * Select a list of tickets that are not closed(2).
         */
        $selection = [ ];

        if ($filter) {
            // TODO - Add extra filtering
        }

        $tickets = Ticket::where('status_id', '!=', '2')->get();

        foreach ($tickets as $ticket) {
            /*
             * Only send a notification if there is something new to report
             * or if this is the first notification.
             */
            if ($ticket->last_notify_count == $ticket->events->count() &&
                $ticket->last_notify_count != 0
            ) {
                continue;
            } else {
                $selection[] = $ticket;
            }
        }

        return $selection;
    }
}
