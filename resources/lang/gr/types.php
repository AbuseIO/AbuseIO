<?php

return [
    // Ticket types
    'type' => [
        'INFO' => [
            'name'          => 'Info',
            'description'   => 'Σας συνιστούμε να επιλύσετε το θέμα αλλα δεν έχει δημιουργηθεί αναφορά κατάχρησης ακόμα',
        ],
        'ABUSE' => [
            'name'          => 'Abuse',
            'description'   => 'Απαιτούμε να επιλύσετε το θέμα άμεσα αλλιώς είμαστε αναγκασμένοι να προβούμε στις απαραίτητες ενέργειες',
        ],
        'ESCALATION' => [
            'name'          => 'Escalation',
            'description'   => 'Λάβαμε τα απαραίτητα μέτρα για την αποφυγή περαιτέρω κατάχρησης. Τα μέτρα θα αρθούν αφού επιλυθεί το θέμα'
        ],
    ],

    // Ticket statuses
    'status' => [
        // Abusedesk ticket statuses
        'abusedesk' => [
            'OPEN' => [
                'name'          => 'Ανοικτά',
                'description'   => 'Ανοικτά δελτία',
            ],
            'CLOSED' => [
                'name'          => 'Κλεισμένα',
                'description'   => 'Κλεισμένα δελτία',
            ],
            'ESCALATED' => [
                'name'          => 'Κλιμακωμένα',
                'description'   => 'Κλιμακωμένα δελτία',
            ],
            'RESOLVED' => [
                'name'          => 'Επιλυμένα',
                'description'   => 'Επιλυμένα δελτία',
            ],
            'IGNORED' => [
                'name'          => 'Αγνοημένα',
                'description'   => 'Αγνοημένα δελτία',
            ],
        ],
        // Contact ticket statuses
        'contact' => [
            'OPEN' => [
                'name'          => 'Ανοικτά',
                'description'   => 'Ανοικτά δελτία',
            ],
            'RESOLVED' => [
                'name'          => 'Επιλυμένα',
                'description'   => 'Επιλυμένα δελτία επαφής',
            ],
            'IGNORED' => [
                'name'          => 'Αγνοημένα',
                'description'   => 'Αγνοημένα δελτία επαφής',
            ],
        ],
    ],

    // Ticket notification states
    'state' => [
        'NOTIFIED' => [
            'name'          => 'Στάλθηκε ειδοποίηση',
            'description'   => 'Δελτία όπου η επαφή έχει ειδοποιηθεί.',
        ],
        'NOT_NOTIFIED' => [
            'name'          => 'Δεν έχει σταλεί ειδοποίηση',
            'description'   => 'Δελτία όπου η επαφή δεν έχει ειδοποιηθεί.',
        ],
    ],
];
