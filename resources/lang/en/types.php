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

        '1' => [
            'name' => 'Open',
            'description' => '',
        ],

        '2' => [
            'name' => 'Closed',
            'description' => '',
        ],

    ],

];
