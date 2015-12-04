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

    'title'                         => 'AbuseIO Zelfhelp',
    'intro'                         => 'You are seeing this page because we have detected suspicious activities'.
                                       ' from your IP address, Domain name or E-Mail address. On this page you'.
                                       ' will find all the information about these activities and the underlying'.
                                       ' problem.',
    'ticket'                        => ' Ticket',

    'menu' => [
        'basic'                     => 'Basis informatie',
        'technical'                 => 'Technische details',
        'about'                     => 'Wat is dit?',
        'communication'             => 'Vragen / Opgelost!',
    ],

    'basic' => [
        'ip'                        => 'IP adres',
        'ptr'                       => 'Reverse DNS',
        'domain'                    => 'Domein naam',
        'class'                     => 'Klassificatie',
        'type'                      => 'Type',
        'firstSeen'                 => 'Eerst gezien',
        'lastSeen'                  => 'Laatst gezien',
        'reportCount'               => 'Rapport teller',
        'ticketStatus'              => 'Ticket status',
        'ticketCreated'             => 'Ticket gemaakt',
        'ticketModified'            => 'Ticket gewijzigd',
        'replyStatus'               => 'Reactie status',
        'suggest'                   => 'Actie vereist',
    ],

    'technical' => [
        'collectError'              => 'Er is een fout ontstaan tijden het verzamelen van de gebeurtenis informatie.',
        'timestamp'                 => 'Gezien',
        'source'                    => 'Bron',
        'information'               => 'Gebeurtenis informatie',
    ],

    'communication' => [
        'header'                    => 'U kunt onderstaand formulier gebruiken om te reageren.',
        'noMessages'                => 'Geen reacties.',
        'placeholder'               => 'Gebruik dit veld om uw vraag te stellen of uw toegepaste oplossing mede te delen.',
        'placeholder_admin'         => 'Reactie richting klant',
        'previousCommunication'     => 'Voorgaande communicatie',
        'responseFrom'              => 'Reactie van',
        'contact'                   => 'Klant',
        'abusedesk'                 => 'Abusedesk',
        'submit'                    => 'Opslaan',
        'reply'                     => 'Reactie',
        'download'                  => 'Download',
        'view'                      => 'Bekijk',
    ],

    'messages' => [
        'alertTest'                 => 'Dit is een alert bericht!',
    ],
];
