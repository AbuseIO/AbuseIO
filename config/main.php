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
        'navigation' => [
            'home'      => 'Home',
            'contacts'  => 'Contacts',
            'netblocks' => 'Netblocks',
            'domains'   => 'Domains',
            'tickets'   => 'Tickets',
            'search'    => 'Search',
            'analytics' => 'Analytics',
        ]
    ],

    'emailparser' => [
        'fallback_mail'                     => 'admin@isp.local',
        'store_mail'                        => true,
        'store_evidence'                    => true,
        'remove_evidence'                   => '500 days',
        /*
         * Moving into parser config itself
        'sender_map'                        => [

            '/summaries@admin.spamcop.net/'         => 'spamcop',
            '/@reports.spamcop.net/'                => 'spamcop',
            '/@ip-echelon.com/'                     => 'ip_echelon',
            '/monitor-bounce@projecthoneypot.org/'  => 'project_honeypot',
            '/@junkemailfilter.com/'                => 'junkemailfilter_com',
            '/@r.iecc.com/'                         => 'iecc_com',
            '/abuse@clean-mx.de/'                   => 'cleanmx_de',
            '/@USGOabuse.net/'                      => 'usgoabuse',
            '/abuse-reports@cyscon.de/'             => 'cyscon',
            '/takedown-response.*@netcraft.com/'    => 'netcraft',
            '/noreply@spamlogin.com/'               => 'spamexperts',
            '/reports@reports.abusehub.nl/'         => 'abusehub',
        ],
        'body_map'                          => [
            '/User-Agent: Spampanel/'               => 'spamexperts',
            '/User-Agent: SpamExperts/'             => 'spamexperts',
        ],
        */
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
