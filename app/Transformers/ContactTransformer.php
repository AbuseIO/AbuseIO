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
     */
    public function transform(Contact $contact)
    {
        return [
            'reference' => (string) $contact->reference,
            'name' => (string) $contact->name,
            'email' => (string) $contact->email,
            'api_host' => (string) $contact->api_host,
            'auto_notify' => (bool) $contact->auto_notify,
            'enabled' => (bool) $contact->enabled,
            //'account' => (new AccountTransformer)->transform($this->account);
        ];
    }
}
