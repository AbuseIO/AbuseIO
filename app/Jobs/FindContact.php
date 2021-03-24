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
        $contact = new Contact();
        $contact->reference = 'UNDEF';
        $contact->name = 'Undefined Contact';
        $contact->enabled = true;
        $contact->email = '';
        $contact->api_host = '';
        $contact->account()->associate(Account::getSystemAccount());

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

        $validation = Validator::make($contact->toArray(), Contact::validateRules($contact));

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
     * Return the contact from the external method;.
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

        foreach (config("main.external.findcontact.{$section}") as $config) {
            // skip part on invalid config or 'prefer_local' param
            if (
                !is_array($config) ||
                !array_key_exists('class', $config) ||
                !array_key_exists('method', $config)
            ) {
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

                    if (!empty($contact) && self::validateContact($contact)) {
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
     * @param string $ip    IP address
     * @param bool   $local only local lookup
     *
     * @return object
     */
    public static function byIP($ip, $local = false)
    {
        return self::getContact(
            'ip',
            $ip,
            Netblock::where('first_ip_int', '<=', inetPtoi($ip))
                ->where('last_ip_int', '>=', inetPtoi($ip))
                ->where('enabled', '=', true)
                ->orderBy('first_ip_int', 'desc')
                ->orderBy('last_ip_int', 'asc')
                ->take(1),
            $local
        );
    }

    /**
     * Return contact by Domain.
     *
     * @param string $domain domain name
     * @param bool   $local  only local lookup
     *
     * @return object
     */
    public static function byDomain($domain, $local = false)
    {
        return self::getContact(
            'domain',
            $domain,
            Domain::where('name', '=', $domain)
                ->where('enabled', '=', true)
                ->take(1),
            $local
        );
    }

    /**
     * Return contact by Code.
     *
     * @param string $id    contact reference
     * @param bool   $local only local lookup
     *
     * @return object
     */
    public static function byId($id, $local = false)
    {
        return self::getContact(
            'id',
            $id,
            Contact::where('reference', '=', $id)
                ->where('enabled', '=', true)
                ->take(1),
            $local
        );
    }

    /**
     * Helper method that retrieves the external or internal contact
     * does most of the work ;).
     *
     * @param string $type ip, domain or id
     * @param string $term search the contact for this term
     * @param $local_query $query to retrieve the local contact
     * @param bool $local only return the local contact
     *
     * @return object
     */
    public static function getContact($type, $term, $local_query, $local)
    {
        $contact = self::undefined();

        // internal lookup
        $result = $local_query->get();

        if (isset($result[0])) {
            $contact = $result[0]->contact;
        }

        if ($local) {
            // early return if the local flag is called, prevent loops
            return $contact;
        }

        // external lookup
        $external_contact = self::getExternalContact($type, $term);

        // if external lookups are preferred or if the local lookup fails
        // and the external lookup succeeded return the external lookup
        if (((config("main.external.findcontact.{$type}.prefer_local") === false) ||
                (config("main.external.findcontact.{$type}.prefer_local") === true &&
                    $contact->reference === 'UNDEF')) &&
            (!empty($external_contact))) {
            $contact = $external_contact;
        }

        return $contact;
    }
}
