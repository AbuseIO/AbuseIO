<?php

return [
    // Ticket types
    'type' => [
        'INFO' => [
            'name'          => 'Info',
            'description'   => 'We adviseren uw ten zeerste dit probleem te verhelpen. Er is op dit moment nog geen '.
                               'abuse gemeld.',
        ],
        'ABUSE' => [
            'name'          => 'Abuse',
            'description'   => 'Uw interventie is vereist, anders zijn we genoodzaakt zelf in te grijpen.',
        ],
        'ESCALATED' => [
            'name'          => 'Escalatie',
            'description'   => 'We hebben stappen ondernomen om verdere misbruik te voorkomen. Deze limitaties '.
                               'zullen worden opgeheven als u uw probleem heeft opgelost.',
        ],
    ],

    // Ticket statuses
    'status' => [
        // Abusedesk ticket statuses
        'abusedesk' => [
            'OPEN' => [
                'name'          => 'Open',
                'description'   => 'Open tickets',
            ],
            'CLOSED' => [
                'name'          => 'Gesloten',
                'description'   => 'Gesloten tickets',
            ],
            'ESCALATED' => [
                'name'          => 'Geëscaleerd',
                'description'   => 'Geëscaleerde tickets',
            ],
            'RESOLVED' => [
                'name'          => 'Opgelost',
                'description'   => 'Opgeloste tickets',
            ],
            'IGNORED' => [
                'name'          => 'Genegeerd',
                'description'   => 'Genegeerde tickets',
            ],
        ],
        // Customer ticket statuses
        'customer' => [
            'OPEN' => [
                'name'          => 'Open',
                'description'   => 'Open tickets',
            ],
            'RESOLVED' => [
                'name'          => 'Opgelost',
                'description'   => 'Klant opgeloste tickets',
            ],
            'IGNORED' => [
                'name'          => 'Genegeerd',
                'description'   => 'Klant genegeerde tickets',
            ],
        ],
    ],

    // Ticket notification states
    'state' => [
        'NOTIFIED' => [
            'name'          => 'Aangemeld bij klant',
            'description'   => 'Tickets welke zijn aangemeld bij de klant.'
        ],
        'NOT_NOTIFIED' => [
            'name'          => 'Niet aangemeld bij klant',
            'description'   => 'Tickets welke nog niet zijn aangemeld bij de klant.'
        ],
    ]
];
