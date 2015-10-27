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
     * Return the class and methdod to do external calls
     * @return object
     */
    public static function getExternalResolver($section, $search)
    {
        if (!empty(config("main.resolvers.findcontact.{$section}.class"))
            && !empty(config("main.resolvers.findcontact.{$section}.method"))
        ) {
            $class = 'AbuseIO::FindContact::' . config("main.resolvers.findcontact.{$section}.class");
            $method = '\\' . str_replace('::', '\\', $class) . '->'
                . config("main.resolvers.findcontact.{$section}.method");

            if (class_exists($class) === true && is_callable($method) === true) {
                $reflectionMethod = new ReflectionMethod($class, $method);
                $resolver = $reflectionMethod->invoke(new $$method, $search);

                if (!empty($resolver)) {
                    return $resolver;
                }
            }
        }

        return false;
    }

    /**
     * Return contact by Netblock
     * @param  string $ip
     * @return object
     */
    public static function byIP($ip)
    {
        $result = Netblock::
        where('first_ip', '<=', ICF::inetPtoi($ip))
            ->where('last_ip', '>=', ICF::inetPtoi($ip))
            ->where('enabled', '=', true)
            ->orderBy('first_ip', 'desc')
            ->orderBy('last_ip', 'asc')
            ->take(1)
            ->get();

        if (isset($result[0])) {
            return $result[0]->contact;
        }

        $findContact = FindContact::getExternalResolver('ip', $ip);
        if (!empty($findContact)) {
            return $findContact;
        }

        return FindContact::undefined();
    }

    /**
     * Return contact by Domain
     * @param  string $domainName
     * @return object
     */
    public static function byDomain($domain)
    {
        $result = Domain::where('name', '=', $domain)
                    ->where('enabled', '=', true)
                    ->take(1)
                    ->get();

        if (isset($result[0])) {
            return $result[0]->contact;
        }

        $findContact = FindContact::getExternalResolver('domain', $domain);
        if (!empty($findContact)) {
            return $findContact;
        }

        return FindContact::undefined();
    }

    /**
     * Return contact by Code
     * @param  string $reference
     * @return object
     */
    public static function byId($id)
    {
        $result = Contact::where('reference', '=', $id)
                    ->where('enabled', '=', true)
                    ->take(1)
                    ->get();

        if (isset($result[0])) {
            return $result[0];
        }

        $findContact = FindContact::getExternalResolver('id', $id);
        if (!empty($findContact)) {
            return $findContact;
        }

        return FindContact::undefined();
    }
}
