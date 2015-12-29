<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Netblock;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Contact;
use ReflectionMethod;
use Validator;
use ICF;
use Log;

/**
 * This FindContact class provide lookup methods to find contacts
 *
 * Class FindContact
 */
class FindContact extends Job
{
    /**
     * Return undefined contact
     *
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
        $contact->api_host      = '';
        $contact->api_key       = '';
        $contact->account_id    = 1;

        return $contact;
    }

    /**
     * Validates an contact object before passing it along. On error it will return UNDEF
     *
     * @param  object $contact
     * @return boolean $valid
     */
    public static function validateContact($contact)
    {
        $validation = Validator::make($contact->toArray(), Contact::createRules($contact));

        if ($validation->fails()) {
            $messages = implode(' ', $validation->messages()->all());

            Log::error(
                '(JOB ' . getmypid() . ') FindContact: ' .
                "A contact object that was returned was not correctly formatted ({$messages}). Falling back to UNDEF"
            );

            return false;
        }

        return true;
    }

    /**
     * Return the class and methdod to do external calls
     *
     * @param string $section
     * @param string $search
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

                if (!empty($resolver) && self::validateContact($resolver)) {
                    return $resolver;
                }
            }
        }

        return false;
    }

    /**
     * Return contact by Netblock
     *
     * @param  string $ip
     * @return object
     */
    public static function byIP($ip)
    {
        // If local lookups are not preferred, then do the remote lookup first
        if (config('main.external.prefer_local') === false) {
            $findContact = FindContact::getExternalContact('ip', $ip);
            if (!empty($findContact)) {
                return $findContact;
            }
        }

        // Do a local lookup
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

        // Do a remote lookup, if local lookups are preferred. Else skip this as this was already done.
        if (config('main.external.prefer_local') === true) {
            $findContact = FindContact::getExternalContact('ip', $ip);
            if (!empty($findContact)) {
                return $findContact;
            }
        }

        return FindContact::undefined();
    }

    /**
     * Return contact by Domain
     *
     * @param  string $domainName
     * @return object
     */
    public static function byDomain($domain)
    {
        // If local lookups are not preferred, then do the remote lookup first
        if (config('main.external.prefer_local') === false) {
            $findContact = FindContact::getExternalContact('domain', $domain);
            if (!empty($findContact)) {
                return $findContact;
            }
        }

        // Do a local lookup
        $result = Domain::where('name', '=', $domain)
                    ->where('enabled', '=', true)
                    ->take(1)
                    ->get();

        if (isset($result[0])) {
            return $result[0]->contact;
        }

        // Do a remote lookup, if local lookups are preferred. Else skip this as this was already done.
        if (config('main.external.prefer_local') === true) {
            $findContact = FindContact::getExternalContact('domain', $domain);
            if (!empty($findContact)) {
                return $findContact;
            }
        }

        return FindContact::undefined();
    }

    /**
     * Return contact by Code
     *
     * @param  string $reference
     * @return object
     */
    public static function byId($id)
    {
        // If local lookups are not preferred, then do the remote lookup first
        if (config('main.external.prefer_local') === false) {
            $findContact = FindContact::getExternalContact('id', $id);
            if (!empty($findContact)) {
                return $findContact;
            }
        }

        // Do a local lookup
        $result = Contact::where('reference', '=', $id)
                    ->where('enabled', '=', true)
                    ->take(1)
                    ->get();

        if (isset($result[0])) {
            return $result[0];
        }

        // Do a remote lookup, if local lookups are preferred. Else skip this as this was already done.
        if (config('main.external.prefer_local') === true) {
            $findContact = FindContact::getExternalContact('id', $id);
            if (!empty($findContact)) {
                return $findContact;
            }
        }

        return FindContact::undefined();
    }
}
