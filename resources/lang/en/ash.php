<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ASH Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the ASH views
    | This excludes the general field mapping of names (like data from info blobs)
    */

    'title'                         => 'ASH - Ticket',
    'intro'                         => 'You are seeing this page because we have detected suspicious activities'.
                                       ' from your IP address, Domain name or E-Mail address. On this page you'.
                                       ' will find all the information about these activities and the underlying'.
                                       ' problem.',

    'menu' => [
        'basic'                     => 'Basic Information',
        'technical'                 => 'Technical Details',
        'about'                     => 'What is this?',
        'communication'             => 'Questions / Resolved!',
    ],

    'basic' => [
        'ip'                        => 'IP address',
        'ptr'                       => 'Reverse DNS',
        'domain'                    => 'Domain name',
        'class'                     => 'Classification',
        'type'                      => 'Type',
        'firstSeen'                 => 'First seen',
        'lastSeen'                  => 'Last seen',
        'reportCount'               => 'Report count',
        'ticketStatus'              => 'Ticket status',
        'replyStatus'               => 'Reply status',
        'suggest'                   => 'Action required',
    ],

    'technical' => [
        'collectError'              => 'An error occurred while collecting event information',
        'timestamp'                 => 'Seen',
        'source'                    => 'Source',
        'information'               => 'Event information',
    ],

    'communication' => [
        'header'                    => 'You can use the below form to reply to this ticket or implemented solution and close the ticket.',
        'noMessages'                => 'No interaction has been done yet',
        'reply'                     => 'Reply',
        'placeholder'               => 'Use this text box to ask your question or your applied solution.',
        'submit'                    => 'Submit',
        'previousCommunication'     => 'Previous communication',
        'responseFrom'              => 'Response from',
        'contact'                   => 'Customer',
        'abusedesk'                 => 'Abusedesk',

    ],

    'messages' => [
        'alertTest'                 => 'This is an alert message',
    ],

];
