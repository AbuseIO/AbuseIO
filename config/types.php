<?php

/*
 * Types.php:
 * This file described the types of tickets, the ticket statuses
 * (abusedesk and customer) and the notification states of a ticket.
 * Translations for these types/statuses can be done with the file:
 * resources/lang/../types.php
 */

return [
    // Possible ticket types
    'type' => [
        'INFO',
        'ABUSE',
        'ESCALATION',
    ],

    // Possible ticket statuses
    'status' => [
         // Abusedesk ticket statuses
        'abusedesk' => [
            'OPEN' => [
                'class' => 'primary',
            ],
            'CLOSED' => [
                'class' => 'info',
            ],
            'ESCALATED' => [
                'class' => 'danger',
            ],
            'RESOLVED' => [
                'class' => 'success',
            ],
            'IGNORED' => [
                'class' => 'default',
            ],
        ],
        /*
         * Customer ticket statuses
         * When the ticket type is INFO; setting the customer status to
         * RESOLVED or IGNORED will stop sending notifications. All other
         * ticket types will ignore this status and is purely informational
         * towards the Abusedesk.
         */
        'customer' => [
            'OPEN' => [
                'class' => 'primary',
            ],
            'RESOLVED' => [
                'class' => 'success',
            ],
            'IGNORED' => [
                'class' => 'warning',
            ]
        ],
    ],

    /*
     * Notification statuses
     * This status defines if the latest incoming notification has also been
     * send to the customer.
     */
    'state' => [
        'NOTIFIED',
        'NOT_NOTIFIED',
    ]
];
