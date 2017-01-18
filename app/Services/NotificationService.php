<?php
namespace AbuseIO\Services;

use AbuseIO\Models\Contact;
use AbuseIO\Notification\Factory;

class NotificationService
{
    public function listAll()
    {
        return Factory::getNotification();
    }

    public function listForContact(Contact $contact)
    {
        return [];
    }

    public function hasNotificationMethod($contact, $method)
    {
        if (is_null($contact)) {
            return false;
        }

        return $contact->hasNotificationMethod($method);
    }
}