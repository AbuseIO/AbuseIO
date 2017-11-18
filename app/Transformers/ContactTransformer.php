<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\Contact;
use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract
{
    /**
     * converts the Contact object to a generic array.
     *
     * @param Contact $contact
     *
     * @return array
     *
     * @TODO auto_notify should be remapped as Notification_methods;
     */
    public function transform(Contact $contact)
    {
        return [
            'id'          => (int) $contact->id,
            'reference'   => (string) $contact->reference,
            'name'        => (string) $contact->name,
            'email'       => (string) $contact->email,
            'api_host'    => (string) $contact->api_host,
            'auto_notify' => (bool) $contact->auto_notify(),
            'enabled'     => (bool) $contact->enabled,
            'account_id'  => (int) $contact->account_id,
            //'account' => (new AccountTransformer)->transform($this->account);
        ];
    }
}
