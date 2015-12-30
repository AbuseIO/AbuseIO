<?php

return [

    'type' => [
        1 => [
            'name' => 'Info',
            'description' => 'We strongly suggest you resolve this matter, but no actual abuse has been recorded yet',
        ],
        2 => [
            'name' => 'Abuse',
            'description' => 'We require you to resolve this matter swiftly or we are required to intervene',
        ],
        3 => [
            'name' => 'Escalation',
            'description' => 'We have taken steps to prevent further abuse. These limitations will be lifted after the matter is resolved',
        ],
    ],

    'status' => [
        1 => [
            'name'          => 'Open',
            'description'   => 'Open tickets',
            'class'         => 'primary',
        ],
        2 => [
            'name'          => 'Closed',
            'description'   => 'Closed tickets',
            'class'         => 'success',
        ],
        3 => [
            'name'          => 'Escalated',
            'description'   => 'Escalated tickets',
            'class'         => 'danger',
        ],
        4 => [
            'name'          => 'Ignored',
            'description'   => 'Ignored tickets',
            'class'         => 'default',
        ],
        5 => [
            'name'          => 'Resolved',
            'description'   => 'Resolved tickets',
            'class'         => 'info',
        ],
    ],

    'state' => [
        1 => [
            'name' => 'Notified',
            'description' => 'Tickets where the customer have been nofitied.'
        ],
        2 => [
            'name' => 'Unnotified',
            'description' => 'Tickets where the customer have not been nofitied.'
        ],
    ]
];
