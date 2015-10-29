<?php

/*
 * Each plugin, collector or handler will have a config template and a custom config. We will provide the default
 * settings whereas when a custom config file exists it should be overriding the custom config.
 *
 * Config files should be dumb little buggers, which just a key = value per line. This Laravel config handler will
 * validate the setup and build correctly set arrays, as end user tend to make typo's and we would want total breakage
 * at a core level.
 *
 * TODO : work out how to create a override config file
 * TODO : work out how to create seperate override config file for each module (like parsers, collectors, etc)
 */

return [

    'interface' => [
        'language' => 'en',
        'navigation' => ['home', 'contacts', 'netblocks', 'domains' , 'tickets', 'search', 'analytics']
    ],

    'emailparser' => [
        'fallback_mail'                     => 'admin@isp.local',
        'store_mail'                        => true,
        'store_evidence'                    => true,
        'remove_evidence'                   => '500 days',
        'notify_on_warnings'                => true,
    ],

    'reports' => [
        'match_period'                      => '14 days',
        'close_after'                       => '21 days',
        'resolvable_only'                   => false, // This drops anything if a domain or netblock cannot be found
        'resolvable_netblocks'              => [
            // Overlapping netblocks defined here if IPA only lists IP's that are in use, which is a fallback check.
        ],
    ],

    'notes' => [
        'enabled'                           => true,
        'deletable'                         => true,
        'show_abusedesk_names'              => true,
    ],

    'notifications' => [
        'enabled'                           => true,
        'info_interval'                     => '90 days',
        'abuse_interval'                    => '0 minutes',
        'min_lastseen'                      => '30 days',
        'template'                          => '/etc/mail.template',
        'from_address'                      => 'abuse@isp.local',
        'from_name'                         => 'ISP Abusedesk',
        'bcc_enabled'                       => false,
        'bcc_address'                       => 'management@isp.local',
    ],

    'ash' => [
        'url'                               => 'https://abuseio.isp.local/ash/'
    ],

    'resolvers' => [
        'findcontact'                      => [
            'id' => [
                'class'                     => 'Custom',
                'method'                    => 'getContactById'
            ],
            'ip' => [
                'class'                     => 'Custom',
                'method'                    => 'getContactByIp'
            ],
            'domain' => [
                'class'                     => 'Custom',
                'method'                    => 'getContactByDomain'
            ],
        ],
    ],

];
