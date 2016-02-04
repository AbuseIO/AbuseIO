# Notifications

Once you have installed AbuseIO you will notice it would have come with an installed package called 'notification-mail'
which handles the e-mail notifications to your contacts. If you were running 3.x before then this will seem somewhat
familiar, however things have improved quite a bit and there are few things you should be aware of.

## Housekeeper

The housekeeper is responsible for sending these notifications and it will call each notification method that has
been enabled. You can enable as many methods as you like, each will be called separately.

## Main configuration

You will find a few elements in the main configuration that can tune the notifications:

    'notifications' => [
        'enabled'                           => true,
        'info_interval'                     => '90 days',
        'abuse_interval'                    => '0 minutes',
        'min_lastseen'                      => '14 days',
        'from_address'                      => 'abuse@isp.local',
        'from_name'                         => 'ISP Abusedesk',
        'bcc_enabled'                       => false,
        'bcc_address'                       => 'management@isp.local',
    ],

    'housekeeping' => [
        'notifications_cron'                => '*/15 * * * * *',
    ],

## Template configuration

The template for sending an e-mail is generated each time the method is called. We supply a default (english)
configuration which can be found in the vender/abuseio/notification-mail/config/Mail.php.

As with any of the AbuseIO configurations you can create a copy of this file into your environment directory, e.g.
config/production/notifications/Mail.php, and customize your configuration as you wish.

## Notification filters

By default we have encoded the system with filters to allow you to make sure a ticket will not send notifications.
This filter could be set either by:

- The abusedesk, by tagging the ticket as 'IGNORED'. As the abusedesk you can do this for any ticket or class.
- The contact (recipient of the complaint you send) can tag the ticket as CUSTOMER IGNORED, but only if the
ticket has been classified as 'informational'

## IODEF Attachment

By default we also add a full dump of the notified tickets with their events and (not hidden) notes to the recipient.
This way the recipient will be able to import the data into any system they like since all the information will be provided in the XML.