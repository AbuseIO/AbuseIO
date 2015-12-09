<?php

namespace AbuseIO\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use ReflectionMethod;
use AbuseIO\Models\Ticket;
use Validator;
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
        Validator::extend(
            'timestamp',
            function ($attribute, $value, $parameters) {
                $check = (is_int($value) or is_float($value))
                    ? $value
                    : (string) (int) $value;

                return ($check === $value)
                        && ($value <= PHP_INT_MAX)
                        && ($value >= ~PHP_INT_MAX);
            }
        );
    }

    /**
     * Sends out a notification for a single ticket by building it into a array the normal send($notifications) can
     * understand and returns the result. This could be called from the GUI ticket view for example
     *
     * @param  object $ticket
     * @return boolean
     */
    public function sendTicket($ticket)
    {

    }

    /**
     * Sends out notifications based on the configured notification modules.
     * Returns false on failed or havent done anything at all.
     *
     * @param  array $notifications ($notifications[$reference][$type] => array $tickets)
     * @return array
     */
    public function send($notifications)
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
                    $notificationResult = $reflectionMethod->invoke(new $class, $notifications);

                    if ($notificationResult !== true) {
                        Log::error(
                            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                            "Notifications with {$class} did not succeed"
                        );

                    } else {
                        /*
                         * We need to update all the tickets that a notification was send
                         */
                        $return = true;

                        foreach ($notifications as $customerReference => $notificationTypes) {
                            foreach ($notificationTypes as $notificationType => $tickets) {
                                foreach ($tickets as $ticket) {
                                    $ticket->last_notify_count      = $ticket->events->count();
                                    $ticket->last_notify_timestamp  = time();
                                    $ticket->notified_count         = $ticket->notified_count + 1;
                                    //$ticket->save();
                                }
                            }
                        }

                        Log::debug(
                            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                            "Notifications with {$class} was successfull for contact reference: " . key($notifications)
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
        } else {
            Log::debug(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                "No notification methods are configured, no sense into calling unexisting methods"
            );
        }

        return $return;
    }

    public function walkList()
    {

        if (empty(config("main.external.notifications"))
            && !is_array(config("main.external.notifications"))
        ) {
            Log::debug(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                "No notification methods are configured, no sense into calling unexisting methods"
            );
            return true;
        }

        Log::info(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            "A notification run has been started"
        );

        $notifications = $this->buildList();

        if (empty($notifications)) {
            Log::info(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                "No contacts that need notifications"
            );
            return true;
        }

        $counter = 0;
        $errors = 0;

        foreach ($notifications as $reference => $referenceData) {
            $result = $this->send([$reference => $referenceData]);

            if (!$result) {
                $errors++;
            } else {
                $counter++;
            }
        }

        if ($errors !== 0) {
            Log::debug(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                "Failed sending out {$counter} ticket notifications. Encountered {$errors} errors."
            );
            return false;
        }

        Log::debug(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            "Successfully send out notifications to {$counter} contacts"
        );
        return true;
    }

    /**
     * Create a list of tickets that need outgoing notifications.
     * @return array
     */
    public function buildList()
    {
        /*
         * Select a list of tickets that are not closed(2).
         */
        $selection = [ ];

        $tickets = Ticket::where('status_id', '!=', '2')->get();

        $validator = Validator::make(
            [
                'notification info_interval'    => strtotime(config('main.notifications.info_interval') . " ago"),
                'notification abuse_interval'   => strtotime(config('main.notifications.abuse_interval') . " ago"),
                'notification min_lastseen'     => strtotime(config('main.notifications.min_lastseen') . " ago"),
            ],
            [
                'notification info_interval'    => 'required|timestamp',
                'notification abuse_interval'   => 'required|timestamp',
                'notification min_lastseen'     => 'required|timestamp',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->messages();

            $message = '';
            foreach ($messages->all() as $messagePart) {
                $message .= $messagePart . PHP_EOL;
            }
            return $message;
        }

        $sendInfoAfter = strtotime(config('main.notifications.info_interval') . " ago");
        $sendAbuseAfter = strtotime(config('main.notifications.abuse_interval') . " ago");
        $sendNotOlderThen = strtotime(config('main.notifications.min_lastseen') . " ago");

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
                /*
                 * Filter outgoing notifications and aggregate them by reference so we can send out a single
                 * notifications for multiple tickets if needed.
                 */
                //echo "ticket : ". $ticket->lastEvent[0]->timestamp . " >= interval : ". $sendNotOlderThen . ' filter:';

                // Skip if type Info (1) and status Ignored (4)
                if (($ticket->type_id == '1' && $ticket->status_id == '4')) {
                    //echo "1" . PHP_EOL;
                    continue;
                }
                // Skip if type Info (1) and last notification was send after info interval
                if (($ticket->type_id == '1' && $ticket->last_notify_timestamp <= $sendInfoAfter)) {
                    //echo "2" . PHP_EOL;
                    continue;
                }
                // Skip if type Info (1) and last notification was send after abuse interval
                if (($ticket->type_id != '1' && $ticket->last_notify_timestamp <= $sendAbuseAfter)) {
                    //echo "3" . PHP_EOL;
                    continue;
                }
                // Skip if the event received is older the minimal last seen
                if (($ticket->lastEvent[0]->timestamp <= $sendNotOlderThen)) {
                    //echo "4" . PHP_EOL;
                    continue;
                }

                // Conditions just for IP contacts
                if (!empty($ticket->ip_contact_reference) &&
                    $ticket->ip_contact_reference != 'UNDEF' &&
                    $ticket->ip_contact_auto_notify == true
                ) {
                    $selection[$ticket->ip_contact_reference]['ip'][] = $ticket;
                }

                // Conditions just for Domain contacts
                if (!empty($ticket->domain_contact_reference) &&
                    $ticket->domain_contact_reference != 'UNDEF' &&
                    $ticket->ip_contact_auto_notify == true &&
                    $ticket->domain_contact_reference != $ticket->ip_contact_reference
                ) {
                    $selection[$ticket->domain_contact_reference]['domain'][] = $ticket;
                }

            }
        }

        return $selection;
    }
}
