<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Netblock;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Contact;
use ReflectionMethod;
use ICF;

class FindContact extends Job
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

        if (!empty(config('main.resolvers.findcontact.ip.class'))
            && !empty(config('main.resolvers.findcontact.ip.method'))
        ) {
            $class = 'AbuseIO::FindContact::' . config('main.resolvers.findcontact.ip.class');
            $method = '\\' . str_replace('::', '\\', $class) . '->' . config('main.resolvers.findcontact.ip.method');
        }

        if (isset($netblock[0])) {
            return $netblock[0]->contact;

        } elseif (!empty($class)
            && (!empty($method))
            && class_exists($class) === true
            && is_callable($method) === true
        ) {
            $reflectionMethod = new ReflectionMethod($class, $method);
            $callback = $reflectionMethod->invoke(new $$method, $ip);

            return (!empty($callback)) ? $callback : FindContact::undefined();
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

        } elseif (class_exists('AbuseIO::FindContact::ByDomain') === true
            && is_callable('\AbuseIO\FindContact\ByDomain->collect') === true
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

        } elseif (class_exists('AbuseIO::FindContact::ById') === true
            && is_callable('\AbuseIO\FindContact\ById->collect') === true
        ) {
            // Call custom function
        }

        return FindContact::undefined();
    }
}
