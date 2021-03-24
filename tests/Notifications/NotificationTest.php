<?php

namespace tests\Notifications;

use AbuseIO\Notification\Factory;
use tests\TestCase;

class NotificationTest extends TestCase
{
    public function testNotificationFactoryForMail()
    {
        $this->assertContains(
            'Mail',
            Factory::getNotification()
        );
    }

    public function testNotificationInConfig()
    {
        // their should be a notifications array in the config,
        // there should be a key for the Mail notifier and an enabled key to be true;
        $this->assertEquals(
            'Mail',
            config('notifications.Mail.notification.name')
        );

        $this->assertTrue(
            config('notifications.Mail.notification.enabled')
        );
    }
}
