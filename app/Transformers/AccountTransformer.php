<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\Account;
use League\Fractal\TransformerAbstract;

class AccountTransformer extends TransformerAbstract
{
    /**
     * converts the account object to a generic array.
     *
     * @param Account $account
     *
     * @return array
     */
    public function transform(Account $account)
    {
        return [
            'id'          => (int) $account->id,
            'name'        => (string) $account->name,
            'description' => (string) $account->description,
            'brand_id'    => (int) $account->brand_id,
            'disabled'    => (bool) $account->disabled,
        ];
    }
}
