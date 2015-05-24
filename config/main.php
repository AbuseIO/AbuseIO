<?php
/*
 * Each plugin, collector or handler will have a config template and a custom config. We will provide the default
 * settings whereas when a custom config file exists it should be overriding the custom config.
 *
 * Config files will be dumb little buggers, which just a key = value per line. This Laravel config handler will
 * validate the setup and build correctly set arrays, as end user tend to make typo's and we would want total breakage
 * at a core level.
 */


Return [

    'emailparser' => [
        'fallback_mail'                     => 'admin@isp.local',
        'store_mail'                        => true,
        'store_evidence'                    => true,
        'remove_evidence'                   => '500 days',
    ],

    'reports' => [
        'match_period'                      => '14 days',
        'close_after'                       => '21 days',
    ],

    'notes' => [
        'enabled'                           => true,
        'deletable'                         => true,
    ],

    'notifications' => [
        'enabled'                           => true,
        'info_interval'                     => '90 days',
        'abuse_interval'                    => '0 minutes',
        'min_lastseen'                      => '30 days',
        'template'                          => '/etc/mail.template',
        'from_address'                      => 'abuse@isp.local',
        'from_name'                         => 'ISP Abusedesk',
        'bcc_address'                       => 'management@isp.local',
    ],

    'ash' => [
        'url'                               => 'https://abuseio.isp.local/ash/'
    ],

    'resolvers' => [
        'contact'                           => 'custom_find_contact'
    ],

];
