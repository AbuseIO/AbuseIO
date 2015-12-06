<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Netblock;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Contact;
use ReflectionMethod;
use ICF;

class FindContact extends Job
{
    /**
     * Return undefined contact
     * @return object
     */
    public static function undefined()
    {
        $contact = new Contact();
        $contact->reference     = 'UNDEF';
        $contact->name          = 'Undefined customer';
        $contact->enabled       = true;
        $contact->auto_notify   = false;
        $contact->email         = '';
        $contact->rpc_host      = '';
        $contact->rpc_key       = '';
        $contact->account_id    = 1;

        return $contact;
    }

    /**
     * Return the class and methdod to do external calls
     * @return object
     */
    public static function getExternalContact($section, $search)
    {
        if (!empty(config("main.external.findcontact.{$section}.class"))
            && !empty(config("main.external.findcontact.{$section}.method"))
        ) {
            $class = '\AbuseIO\FindContact\\' . config("main.external.findcontact.{$section}.class");
            $method = config("main.external.findcontact.{$section}.method");

            if (class_exists($class) === true && method_exists($class, $method) === true) {
                $reflectionMethod = new ReflectionMethod($class, $method);
                $resolver = $reflectionMethod->invoke(new $class, $search);

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
            where('first_ip_int', '<=', ICF::inetPtoi($ip))
            ->where('last_ip_int', '>=', ICF::inetPtoi($ip))
            ->where('enabled', '=', true)
            ->orderBy('first_ip_int', 'desc')
            ->orderBy('last_ip_int', 'asc')
            ->take(1)
            ->get();

        if (isset($result[0])) {
            return $result[0]->contact;
        }

        $findContact = FindContact::getExternalContact('ip', $ip);
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

        $findContact = FindContact::getExternalContact('domain', $domain);
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

        $findContact = FindContact::getExternalContact('id', $id);
        if (!empty($findContact)) {
            return $findContact;
        }

        return FindContact::undefined();
    }
}
