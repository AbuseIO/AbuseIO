<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\Netblock;
use League\Fractal\TransformerAbstract;

class NetblockTransformer extends TransformerAbstract
{
    /**
     * converts the netblock object to a generic array.
     *
     * @param Netblock $netblock
     *
     * @return array
     */
    public function transform(Netblock $netblock)
    {
        return [
            'id'          => (int) $netblock->id,
            'first_ip'    => (string) $netblock->first_ip,
            'last_ip'     => (string) $netblock->last_ip,
            'description' => (string) $netblock->description,
            'contact'     => (new ContactTransformer())->transform($netblock->contact),
            'enabled'     => (bool) $netblock->enabled,
        ];
    }
}
