<?php

return [
    // Ticket types
    'type' => [
        'INFO' => [
            'name'          => 'Info',
            'description'   => 'We strongly suggest you resolve this matter, but no actual abuse has been recorded yet',
        ],
        'ABUSE' => [
            'name'          => 'Abuse',
            'description'   => 'We require you to resolve this matter swiftly or we are required to intervene',
        ],
        'ESCALATION' => [
            'name'          => 'Escalation',
            'description'   => 'We have taken steps to prevent further abuse. These limitations will be lifted after '.
                               'the matter is resolved',
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
                'name'          => 'Closed',
                'description'   => 'Closed tickets',
            ],
            'ESCALATED' => [
                'name'          => 'Escalated',
                'description'   => 'Escalated tickets',
            ],
            'RESOLVED' => [
                'name'          => 'Resolved',
                'description'   => 'Resolved tickets',
            ],
            'IGNORED' => [
                'name'          => 'Ignored',
                'description'   => 'Ignored tickets',
            ],
        ],
        // Customer ticket statuses
        'customer' => [
            'OPEN' => [
                'name'          => 'Open',
                'description'   => 'Open tickets',
            ],
            'RESOLVED' => [
                'name'          => 'Resolved',
                'description'   => 'Customer resolved tickets',
            ],
            'IGNORED' => [
                'name'          => 'Ignored',
                'description'   => 'Customer ignored tickets',
            ],
        ],
    ],

    // Ticket notification states
    'state' => [
        'NOTIFIED' => [
            'name'          => 'Notified',
            'description'   => 'Tickets where the customer has been notified.'
        ],
        'NOT_NOTIFIED' => [
            'name'          => 'Unnotified',
            'description'   => 'Tickets where the customer has not been notified.'
        ],
    ]
];
