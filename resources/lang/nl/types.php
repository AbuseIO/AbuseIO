<?php

return [

    'type' => [
        1 => [
            'name' => 'Info',
            'description' => 'We adviseren uw ten zeerste dit probleem te verhelpen. Er is op dit moment nog geen abuse gemeld.',
        ],
        2 => [
            'name' => 'Abuse',
            'description' => 'Uw interventie is vereist, anders zijn we genoodzaakt zelf in te grijpen.',
        ],
        3 => [
            'name' => 'Escalation',
            'description' => 'We hebben stappen ondernomen om verdere misbruik te voorkomen. Deze limitaties zullen worden opgeheven als u uw probleem heeft opgelost.',
        ],
    ],

    'status' => [
        1 => [
            'name' => 'Open',
            'description' => 'Open tickets',
        ],
        2 => [
            'name' => 'Closed',
            'description' => 'Gesloten tickets',
        ],
        3 => [
            'name' => 'Escalated',
            'description' => 'Geëscaleerd tickets',
        ],
    ],

    'state' => [
        1 => [
            'name' => 'Aangemeld',
            'description' => 'Tickets welke zijn aangemeld bij de klant.'
        ],
        2 => [
            'name' => 'Niet aangemeld',
            'description' => 'Tickets welke nog niet zijn aangemeld bij de klant.'
        ],
    ]
];
