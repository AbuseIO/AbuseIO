<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Account;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Netblock;
use Log;
use ReflectionMethod;
use Validator;

/**
 * This FindContact class provide lookup methods to find contacts.
 *
 * Class FindContact
 */
class FindContact extends Job
{
    /**
     * Return undefined contact.
     *
     * @return object
     */
    public static function undefined()
    {
        $account = Account::system();

        $contact = new Contact();
        $contact->reference = 'UNDEF';
        $contact->name = 'Undefined Contact';
        $contact->enabled = true;
        $contact->auto_notify = false;
        $contact->email = '';
        $contact->api_host = '';
        $contact->account_id = $account->id;

        return $contact;
    }

    /**
     * Validates an contact object before passing it along. On error it will return UNDEF.
     *
     * @param \AbuseIO\Models\Contact $contact
     *
     * @return bool $valid
     */
    public static function validateContact($contact)
    {
        if (!is_object($contact)) {
            Log::error(
                'FindContact: '.
                'Method did not return a Contact object. Falling back to UNDEF'
            );

            return false;
        }
        if (!method_exists($contact, 'toArray')) {
            Log::error(
                'FindContact: '.
                'Method did not return a valid Contact object. Falling back to UNDEF'
            );

            return false;
        }

        $validation = Validator::make($contact->toArray(), Contact::createRules());

        if ($validation->fails()) {
            $messages = implode(' ', $validation->messages()->all());

            Log::error(
                'FindContact: '.
                "A contact object that was returned was not correctly formatted ({$messages}). Falling back to UNDEF"
            );

            return false;
        }

        return true;
    }

    /**
     * Return the contact from the external method;
     *
     * @param string $section
     * @param string $search
     *
     * @return object | false
     */
    public static function getExternalContact($section, $search)
    {
        $result = false;
        $contact = null;

        foreach (config("main.external.findcontact.{$section}") as $config)
        {
            // skip part on invalid config
            if (
                !is_array($config) ||
                !array_key_exists('class', $config) ||
                !array_key_exists('method', $config)
            )
            {
                continue;
            }

            if (!empty($config['class']) &&
                !empty($config['method'])
            ) {
                $class = '\AbuseIO\FindContact\\'.$config['class'];
                $method = $config['method'];

                if (class_exists($class) === true && method_exists($class, $method) === true) {
                    $reflectionMethod = new ReflectionMethod($class, $method);
                    $contact = $reflectionMethod->invoke(new $class(), $search);

                    if (!empty($r) && self::validateContact($r)) {
                        $result = $contact;

                        // exit the loop, when we find a contact
                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Return contact by Netblock.
     *
     * @param string $ip IP address
     *
     * @return object
     */
    public static function byIP($ip)
    {
        // If local lookups are not preferred, then do the remote lookup first
        if (config('main.external.prefer_local') === false) {
            $findContact = self::getExternalContact('ip', $ip);
            if (!empty($findContact)) {
                return $findContact;
            }
        }

        // Do a local lookup
        $result = Netblock::
            where('first_ip_int', '<=', inetPtoi($ip))
            ->where('last_ip_int', '>=', inetPtoi($ip))
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
            $findContact = self::getExternalContact('ip', $ip);
            if (!empty($findContact)) {
                return $findContact;
            }
        }

        return self::undefined();
    }

    /**
     * Return contact by Domain.
     *
     * @param string $domain domain name
     *
     * @return object
     */
    public static function byDomain($domain)
    {
        // If local lookups are not preferred, then do the remote lookup first
        if (config('main.external.prefer_local') === false) {
            $findContact = self::getExternalContact('domain', $domain);
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
            $findContact = self::getExternalContact('domain', $domain);
            if (!empty($findContact)) {
                return $findContact;
            }
        }

        return self::undefined();
    }

    /**
     * Return contact by Code.
     *
     * @param string $id contact reference
     *
     * @return object
     */
    public static function byId($id)
    {
        // If local lookups are not preferred, then do the remote lookup first
        if (config('main.external.prefer_local') === false) {
            $findContact = self::getExternalContact('id', $id);
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
            $findContact = self::getExternalContact('id', $id);
            if (!empty($findContact)) {
                return $findContact;
            }
        }

        return self::undefined();
    }
}
