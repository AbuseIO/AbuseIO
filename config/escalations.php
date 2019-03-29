<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default escalation advance path
    |--------------------------------------------------------------------------
    |
    | the escalation advance path will automatically upgrade a ticket to a
    | higher priority (info, abuse, escalation) depending of its thresholds and
    | if that part of the path is enabled.
    |
    */
    'DEFAULT' => [

        // By default leaving info tickets at info, and dont advance them to abuse
        'abuse' => [
            'enabled'   => false,
            'threshold' => 10,
        ],

        // By default upgrade abuse tickets to escalation once there are 25 or more events
        'escalation' => [
            'enabled'   => true,
            'threshold' => 25,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Feedback Loop (FBL) escalation advance path
    |--------------------------------------------------------------------------
    |
    | This is a specific escalation advance path for the FEEDBACK_LOOP class
    | tickets will be created by the parser als 'info' and will process when
    | the thresholds are hit.
    |
    | Using the info at the ticket start, you will have a less aggressive
    | notification cycle (once in every 90 days). Once there are 25 or more
    | complaints we consider the 'false-positive' factor gone and upgrade it to
    | abuse which will start real-time notifications.
    | After the escalation threshold is hit it will move the status to escalated
    | which can be use for automatic quarantining stuff.
    */
    'FEEDBACK_LOOP' => [

        // By default upgrade info tickets to abuse once there are 25 or more events
        'abuse' => [
            'enabled'   => true,
            'threshold' => env('ESCALATION_THRESHOLD', 25),
        ],
        // By default upgrade abuse tickets to escalation once there are 100 or more events
        'escalation' => [
            'enabled'   => true,
            'threshold' => 100,
        ],
    ],
];
