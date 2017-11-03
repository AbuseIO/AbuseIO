<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\Domain;
use League\Fractal\TransformerAbstract;

class DomainTransformer extends TransformerAbstract
{
    /**
     * converts the domain object to a generic array.
     *
     * @param Domain $domain
     *
     * @return array
     */
    public function transform(Domain $domain)
    {
        return [
            'id'      => (int) $domain->id,
            'name'    => (string) $domain->name,
            'contact' => (new ContactTransformer())->transform($domain->contact),
            'enabled' => (bool) $domain->enabled,
        ];
    }
}
