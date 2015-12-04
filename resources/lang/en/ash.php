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

    'title'                         => 'AbuseIO Self-help',
    'intro'                         => 'You are seeing this page because we have detected suspicious activities'.
                                       ' from your IP address, Domain name or E-Mail address. On this page you'.
                                       ' will find all the information about these activities and the underlying'.
                                       ' problem.',
    'ticket'                        => ' Ticket',


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
        'ticketCreated'             => 'Ticket created',
        'ticketModified'            => 'Ticket modified',
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
        'header'                    => 'You can use the form below to reply to this ticket.',
        'noMessages'                => 'No interaction has been done yet.',
        'placeholder'               => 'Use this text box to ask your question or your applied solution.',
        'placeholder_admin'         => 'Enter a reply to the customer',
        'previousCommunication'     => 'Previous communication',
        'responseFrom'              => 'Response from',
        'contact'                   => 'Customer',
        'abusedesk'                 => 'Abusedesk',
        'submit'                    => 'Submit',
        'reply'                     => 'Reply',
        'download'                  => 'Download',
        'view'                      => 'View',
    ],

    'messages' => [
        'alertTest'                 => 'This is an alert message',
    ],
];
