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
            'name' => 'Escalatie',
            'description' => 'We hebben stappen ondernomen om verdere misbruik te voorkomen. Deze limitaties zullen worden opgeheven als u uw probleem heeft opgelost.',
        ],
    ],

    'status' => [
        1 => [
            'name'          => 'Open',
            'description'   => 'Open tickets',
            'class'         => 'primary',
        ],
        2 => [
            'name'          => 'Gesloten',
            'description'   => 'Gesloten tickets',
            'class'         => 'success',
        ],
        3 => [
            'name'          => 'Geëscaleerd',
            'description'   => 'Geëscaleerde tickets',
            'class'         => 'danger',
        ],
        4 => [
            'name'          => 'Genegeerd',
            'description'   => 'Genegeerde tickets',
            'class'         => 'default',
        ],
        5 => [
            'name'          => 'Opgelost',
            'description'   => 'Opgeloste tickets',
            'class'         => 'info',
        ],
    ],

    'state' => [
        1 => [
            'name' => 'Aangemeld bij klant',
            'description' => 'Tickets welke zijn aangemeld bij de klant.'
        ],
        2 => [
            'name' => 'Niet aangemeld bij klant',
            'description' => 'Tickets welke nog niet zijn aangemeld bij de klant.'
        ],
    ]
];
