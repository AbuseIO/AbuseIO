<?php

return [
    // Ticket types
    'type' => [
        'INFO' => [
            'name'        => 'Info',
            'description' => 'Σας συνιστούμε να επιλύσετε το θέμα αλλα δεν έχει δημιουργηθεί '.
                               'αναφορά κατάχρησης ακόμα',
        ],
        'ABUSE' => [
            'name'        => 'Abuse',
            'description' => 'Απαιτούμε να επιλύσετε το θέμα άμεσα αλλιώς είμαστε αναγκασμένοι '.
                               'να προβούμε στις απαραίτητες ενέργειες',
        ],
        'ESCALATION' => [
            'name'        => 'Escalation',
            'description' => 'Λάβαμε τα απαραίτητα μέτρα για την αποφυγή περαιτέρω κατάχρησης.'.
                               ' Τα μέτρα θα αρθούν αφού επιλυθεί το θέμα',
        ],
    ],

    // Ticket statuses
    'status' => [
        // Abusedesk ticket statuses
        'abusedesk' => [
            'OPEN' => [
                'name'        => 'Ανοικτό',
                'description' => 'Ανοικτό δελτίο',
            ],
            'CLOSED' => [
                'name'        => 'Κλεισμένo',
                'description' => 'Κλεισμένο δελτίο',
            ],
            'ESCALATED' => [
                'name'        => 'Κλιμακωμένο',
                'description' => 'Κλιμακωμένο δελτίο',
            ],
            'RESOLVED' => [
                'name'        => 'Επιλυμένο',
                'description' => 'Επιλυμένο δελτίο',
            ],
            'IGNORED' => [
                'name'        => 'Αγνοημένο',
                'description' => 'Αγνοημένο δελτίο',
            ],
        ],
        // Contact ticket statuses
        'contact' => [
            'OPEN' => [
                'name'        => 'Ανοικτό',
                'description' => 'Ανοικτό δελτίο',
            ],
            'RESOLVED' => [
                'name'        => 'Επιλυμένο',
                'description' => 'Επιλυμένο δελτίο επαφής',
            ],
            'IGNORED' => [
                'name'        => 'Αγνοημένο',
                'description' => 'Αγνοημένο δελτίο επαφής',
            ],
        ],
    ],

    // Ticket notification states
    'state' => [
        'NOTIFIED' => [
            'name'        => 'Στάλθηκε ειδοποίηση',
            'description' => 'Δελτία όπου η επαφή έχει ειδοποιηθεί.',
        ],
        'NOT_NOTIFIED' => [
            'name'        => 'Δεν έχει σταλεί ειδοποίηση',
            'description' => 'Δελτία όπου η επαφή δεν έχει ειδοποιηθεί.',
        ],
    ],
];
