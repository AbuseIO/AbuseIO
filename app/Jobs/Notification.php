<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Ticket;
use AbuseIO\Notification\Factory as NotificationFactory;
use Illuminate\Contracts\Bus\SelfHandling;
use Log;
use Validator;

/**
 * Class Notification provides notification methods.
 *
 * Class Notification
 */
class Notification extends Job implements SelfHandling
{
    public $ticket;

    /**
     * Sends out notifications based on the configured notification modules.
     * Returns false on failed or havent done anything at all.
     *
     * @param array $notifications ($notifications[$reference][$type] => array $tickets)
     *
     * @return array
     */
    public function send($notifications)
    {
        if (!empty(config('notifications'))
            && is_array(config('notifications'))
        ) {
            foreach (config('notifications') as $notificationModule => $notificationConfig) {
                $notification = notificationFactory::create($notificationModule);

                if (config("notifications.{$notificationModule}.notification.enabled") !== true) {
                    return 'disabled';
                }

                if (!$notification) {
                    return false;
                }

                $notificationResult = $notification->send($notifications);

                if ($notificationResult['errorStatus']) {
                    Log::error(
                        get_class($this).': '.
                        "Notifications with {$notificationModule} did not succeed"
                    );

                    return false;
                } else {
                    Log::debug(
                        get_class($this).': '.
                        "Notifications with {$notificationModule} was successfull for contact reference: ".
                        key($notifications)
                    );
                }
            }
        } else {
            Log::debug(
                get_class($this).': '.
                'No notification methods are installed, skipping notifications'
            );

            return false;
        }

        return true;
    }

    /**
     * Walks a list of notifications (build from BuildList).
     *
     * @param array $notifications
     *
     * @return bool
     */
    public function walkList($notifications)
    {
        if (empty(config('notifications'))
            && !is_array(config('notifications'))
        ) {
            Log::debug(
                get_class($this).': '.
                'No notification methods are configured, no sense into calling unexisting methods'
            );

            return true;
        }

        Log::info(
            get_class($this).': '.
            'A notification run has been started'
        );

        if (empty($notifications)) {
            Log::info(
                get_class($this).': '.
                'No contacts that need notifications'
            );

            return true;
        }

        $counter = 0;
        $errors = 0;

        foreach ($notifications as $reference => $referenceData) {
            $result = $this->send([$reference => $referenceData]);

            if ($result === 'disabled') {
                // Do nothing
            } elseif ($result !== true) {
                $errors++;
            } else {
                $counter++;

                /*
                 * We need to update all the tickets that a notification was send
                 */
                foreach ($referenceData as $notificationType => $tickets) {
                    foreach ($tickets as $ticket) {
                        $ticket->last_notify_count = $ticket->events->count();
                        $ticket->last_notify_timestamp = time();
                        if ($notificationType == 'ip') {
                            $ticket->ip_contact_notified_count = $ticket->ip_contact_notified_count + 1;
                        }
                        if ($notificationType == 'domain') {
                            $ticket->domain_contact_notified_count = $ticket->domain_contact_notified_count + 1;
                        }
                        $ticket->save();
                    }
                }
            }
        }

        if ($errors !== 0) {
            Log::debug(
                get_class($this).': '.
                "Failed sending out notifications. Encountered {$errors} errors."
            );

            return false;
        }

        if ($counter === 0) {
            Log::debug(
                get_class($this).': '.
                'None of the notification methods seem to be enabled'
            );

            return true;
        }

        Log::debug(
            get_class($this).': '.
            "Successfully send out notifications to {$counter} contacts"
        );

        return true;
    }

    /**
     * Create a list of tickets that need outgoing notifications.
     *
     * @param int|bool    $ticket    Ticket ID
     * @param string|bool $reference ReferenceName of contact in ticket
     * @param bool|bool   $force     Force sending even when there are no new events
     * @param string      $only      Only send to ('ip', 'domain' or null (both))
     *
     * @return array $notificationList   List of notifications to send
     */
    public function buildList($ticket = false, $reference = false, $force = false, $only = null)
    {
        /*
         * Select a list of tickets that are not closed(2) by default or add ticket / reference
         * conditions if options were passed along.
         */
        $selection = [];

        $search = Ticket::where('id', '>', '0');

        if (!$force) {
            $search = Ticket::where('status_id', '!=', 'CLOSED');
        }

        if ($ticket !== false) {
            $search->where('id', '=', $ticket);
        }

        if ($reference !== false) {
            $search->where('ip_contact_reference', '=', $reference)
                ->orwhere('domain_contact_reference', '=', $reference);
        }

        $tickets = $search->get();

        $validator = Validator::make(
            [
                'notification info_interval'    => strtotime(config('main.notifications.info_interval').' ago'),
                'notification abuse_interval'   => strtotime(config('main.notifications.abuse_interval').' ago'),
                'notification min_lastseen'     => strtotime(config('main.notifications.min_lastseen').' ago'),
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
                $message .= $messagePart.PHP_EOL;
            }

            return $message;
        }

        // If an invalid value for $only is given, set default (null = both)
        if (!in_array($only, ['ip', 'domain'])) {
            $only = null;
        }

        $sendInfoAfter = strtotime(config('main.notifications.info_interval').' ago');
        $sendAbuseAfter = strtotime(config('main.notifications.abuse_interval').' ago');
        $sendNotOlderThen = strtotime(config('main.notifications.min_lastseen').' ago');

        foreach ($tickets as $ticket) {
            /*
             * Only send a notification if there is something new to report
             * or if this is the first notification.
             */
            if ($ticket->last_notify_count == $ticket->events->count() &&
                $ticket->last_notify_count != 0 &&
                $force !== true
            ) {
                continue;
            } else {
                /*
                 * Filter outgoing notifications and aggregate them by reference so we can send out a single
                 * notifications for multiple tickets if needed.
                 */
                if ($force !== true) {
                    // Skip if status Ignored
                    if ($ticket->status_id == 'IGNORED') {
                        continue;
                    }

                    // Skip if type Info and contact status Ignored
                    if ($ticket->type_id == 'INFO' && $ticket->contact_status_id == 'IGNORED') {
                        continue;
                    }

                    // Skip if type Info (1) and last notification was send after info interval
                    if ($ticket->last_notify_count != 0 &&
                        $ticket->type_id == 'INFO' &&
                        $ticket->last_notify_timestamp >= $sendInfoAfter
                    ) {
                        continue;
                    }

                    // Skip if type Info (1) and last notification was send after abuse interval
                    if ($ticket->last_notify_count != 0 &&
                        $ticket->type_id != 'INFO' &&
                        $ticket->last_notify_timestamp >= $sendAbuseAfter
                    ) {
                        continue;
                    }

                    // Skip if the event received is older the minimal last seen
                    if ($ticket->lastEvent[0]->timestamp <= $sendNotOlderThen) {
                        continue;
                    }

                    // Conditions just for IP contacts
                    if (!empty($ticket->ip_contact_reference) &&
                        $ticket->ip_contact_reference != 'UNDEF' &&
                        $ticket->ip_contact_auto_notify == true &&
                        $only != 'domain'
                    ) {
                        $selection[$ticket->ip_contact_reference]['ip'][] = $ticket;
                    }

                    // Conditions just for Domain contacts
                    if (!empty($ticket->domain_contact_reference) &&
                        $ticket->domain_contact_reference != 'UNDEF' &&
                        $ticket->domain_contact_auto_notify == true &&
                        $ticket->domain_contact_reference != $ticket->ip_contact_reference &&
                        $only != 'ip'
                    ) {
                        $selection[$ticket->domain_contact_reference]['domain'][] = $ticket;
                    }
                } else {
                    /*
                     * Notifications are forced, therefor we skip all the checks except empty/undef
                     */
                    // Conditions just for IP contacts
                    if (!empty($ticket->ip_contact_reference) &&
                        $ticket->ip_contact_reference != 'UNDEF' &&
                        $only != 'domain'
                    ) {
                        $selection[$ticket->ip_contact_reference]['ip'][] = $ticket;
                    }

                    // Conditions just for Domain contacts
                    if (!empty($ticket->domain_contact_reference) &&
                        $ticket->domain_contact_reference != 'UNDEF' &&
                        $ticket->domain_contact_reference != $ticket->ip_contact_reference &&
                        $only != 'ip'
                    ) {
                        $selection[$ticket->domain_contact_reference]['domain'][] = $ticket;
                    }
                }
            }
        }

        return $selection;
    }
}
