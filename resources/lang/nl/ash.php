<?php

/**
 * Translations for AbuseIO Self Help (ash).
 */
return [
    'title' => 'AbuseIO Zelfhulp',
    'intro' => 'U krijgt deze pagina te zien omdat we verdachte activiteiten hebben'.
                                       ' waargenomen vanaf uw IP adres, domeinnaam of e-mail adres. Op deze'.
                                       ' pagina vind u alle informatie over deze activiteiten en het probleem.',
    'ticket' => ' Ticket',

    'menu' => [
        'basic'         => 'Basis informatie',
        'technical'     => 'Technische details',
        'about'         => 'Wat is dit?',
        'communication' => 'Vragen / Opgelost!',
    ],

    'basic' => [
        'ip'             => 'IP',
        'ipAddress'      => 'IP adres',
        'ptr'            => 'Reverse DNS',
        'domain'         => 'Domein',
        'domainName'     => 'Domein naam',
        'class'          => 'Klassificatie',
        'type'           => 'Type',
        'firstSeen'      => 'Eerst gezien',
        'lastSeen'       => 'Laatst gezien',
        'reportCount'    => 'Rapport teller',
        'ticketStatus'   => 'Ticket status',
        'ticketCreated'  => 'Ticket gemaakt',
        'ticketModified' => 'Ticket gewijzigd',
        'replyStatus'    => 'Reactie status',
        'suggest'        => 'Actie vereist',
    ],

    'technical' => [
        'collectError' => 'Er is een fout ontstaan tijden het verzamelen van de gebeurtenis informatie.',
        'timestamp'    => 'Gezien',
        'source'       => 'Bron',
        'information'  => 'Gebeurtenis informatie',
    ],

    'communication' => [
        'header'                => 'U kunt onderstaand formulier gebruiken om te reageren.',
        'noMessages'            => 'Geen reacties.',
        'placeholder'           => 'Gebruik dit veld om uw vraag te stellen of uw toegepaste oplossing mede te delen.',
        'placeholder_admin'     => 'Reactie richting klant',
        'previousCommunication' => 'Voorgaande communicatie',
        'responseFrom'          => 'Reactie van',
        'contact'               => 'Contact',
        'abusedesk'             => 'Abusedesk',
        'submit'                => 'Opslaan',
        'reply'                 => 'Reactie',
        'download'              => 'Download',
        'view'                  => 'Bekijk',
        'closed'                => 'Het is niet mogelijk om een reactie te geven op dit ticket, omdat het ticket gesloten is of de reacties zijn uitgeschakeld.',
        'unchanged'             => 'Ticket status niet veranderen',
        'open'                  => 'Ticket status open laten',
        'resolved'              => 'Ik heb het probleem opgelost',
        'ignored'               => 'Dit probleem kan worden genegeerd',
    ],

    'messages' => [
        'alertTest' => 'Dit is een alert bericht!',
        'noteAdded' => 'Het bericht is toegevoegd aan het ticket',
        'noteEmpty' => 'U kunt geen lege berichten toevoegen!',
    ],
];
