<?php

namespace AbuseIO\Services;

use AbuseIO\Models\Contact;
use AbuseIO\Notification\Factory;

class NotificationService
{
    /**
     * @return array
     */
    public function listAll()
    {
        return Factory::getNotification();
    }

    /**
     * @param Contact $contact
     *
     * @return array
     */
    public function listForContact(Contact $contact)
    {
        return $contact->notificationMethods->map(function ($item) {
            return $item['method'];
        })->filter(function ($item) {
            return in_array($item, $this->listAll());
        })->toArray();
    }

    /**
     * @param $method
     *
     * @return bool
     */
    public static function isValidMethod($method)
    {
        $obj = new static();

        return in_array($method, $obj->listAll());
    }

    /**
     * @param $contact
     * @param $method
     *
     * @return bool
     */
    public function hasNotificationMethod($contact, $method)
    {
        if (is_null($contact)) {
            return false;
        }

        return $contact->hasNotificationMethod($method);
    }
}
