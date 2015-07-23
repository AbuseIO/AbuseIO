<?php

namespace AbuseIO\Commands;

use AbuseIO\Models\Netblock;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Contact;
use ICF;

class FindContact extends Command
{
    // Todo add custom lookup module (package / own repo template)

    /**
     * Return undefined contact
     * @return object
     */
    public static function undefined()
    {
        $contact = new Contact;
        $contact->reference     = 'UNDEF';
        $contact->name          = 'Undefined customer';
        $contact->enabled       = true;
        $contact->auto_notify   = false;
        $contact->email         = 'undef@isp.local';
        $contact->rpc_host      = 'https://under.isp.local/rpc/';
        $contact->rpc_key       = 'idkfaiddqd';

        return $contact;
    }

    /**
     * Return contact by Netblock
     * @param  string $ip
     * @return object
     */
    public static function byIP($ip)
    {
        $netblock = Netblock::
        where('first_ip', '<=', ICF::inetPtoi($ip))
            ->where('last_ip', '>=', ICF::inetPtoi($ip))
            ->where('enabled', '=', true)
            ->orderBy('first_ip', 'desc')
            ->orderBy('last_ip', 'asc')
            ->take(1)
            ->get();

        if (isset($netblock[0])) {
            return $netblock[0]->contact;

        } elseif (class_exists('AbuseIO::Custom::FindContact') === true
            && is_callable('\AbuseIO\Custom\FindContact->byIP') === true
        ) {
            // Call custom function
        }

        return FindContact::undefined();
    }

    /**
     * Return contact by Domain
     * @param  string $domainName
     * @return object
     */
    public static function byDomain($domainName)
    {
        $domain = Domain::where('name', '=', $domainName)
                    ->where('enabled', '=', true)
                    ->take(1)
                    ->get();

        if (isset($domain[0])) {
            return $domain[0]->contact;

        } elseif (class_exists('AbuseIO::Custom::FindContact') === true
            && is_callable('\AbuseIO\Custom\FindContact->byDomain') === true
        ) {
            // Call custom function
        }

        return FindContact::undefined();
    }

    /**
     * Return contact by Code
     * @param  string $reference
     * @return object
     */
    public static function byCode($reference)
    {
        $contact = Contact::where('reference', '=', $reference)
                    ->where('enabled', '=', true)
                    ->take(1)
                    ->get();

        if (isset($contact[0])) {
            return $contact[0];

        } elseif (class_exists('AbuseIO::Custom::FindContact') === true
            && is_callable('\AbuseIO\Custom\FindContact->byCode') === true
        ) {
            // Call custom function
        }

        return FindContact::undefined();
    }
}
