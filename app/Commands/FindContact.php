<?php

namespace AbuseIO\Commands;

use AbuseIO\Models\Netblock;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Contact;
use ICF;

class FindContact extends Command
{

    // Todo add custom lookup module (package / own repo template)

    static public function undefined()
    {

        $contact = new Contact;
        $contact->reference     = 'UNDEF';
        $contact->name          = 'Undefined customer';
        $contact->enabled       = true;
        $contact->auto_notify   = false;
        $contact->email         = '';
        $contact->rpc_host      = '';
        $contact->rpc_key       = '';

        return $contact;

    }

    static public function byIP($ip)
    {

        $netblock = Netblock::
        where('first_ip', '<=', ICF::inet_ptoi($ip))
            ->where('last_ip', '>=', ICF::inet_ptoi($ip))
            ->where('enabled', '=', true)
            ->orderBy('first_ip', 'desc')
            ->orderBy('last_ip', 'asc')
            ->take(1)
            ->get();

        if (isset($netblock[0])) {

            return $netblock[0]->contact;

        } elseif (
            class_exists('AbuseIO::Custom::FindContact') === true &&
            is_callable('\AbuseIO\Custom\FindContact->byIP') === true
        ) {

            // Call custom function

        } else {

            return FindContact::undefined();

        }


    }

    static function byDomain($domainName)
    {

        $domain = Domain::
        where('name', '=', $domainName)
            ->where('enabled', '=', true)
            ->take(1)
            ->get();

        if (isset($domain[0])) {

            return $domain[0]->contact;

        } elseif (
            class_exists('AbuseIO::Custom::FindContact') === true &&
            is_callable('\AbuseIO\Custom\FindContact->byDomain') === true
        ) {

            // Call custom function

        } else {

            return FindContact::undefined();

        }

    }

    static function byCode($reference)
    {

        $contact = Contact::
        where('reference', '=', $reference)
            ->where('enabled', '=', true)
            ->take(1)
            ->get();

        if (isset($contact[0])) {

            return $contact[0];

        } elseif (
            class_exists('AbuseIO::Custom::FindContact') === true &&
            is_callable('\AbuseIO\Custom\FindContact->byReference') === true
        ) {

            // Call custom function

        } else {

            return FindContact::undefined();

        }

    }

}