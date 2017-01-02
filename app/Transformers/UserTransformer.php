<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * converts the user object to a generic array.
     *
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'         => (int) $user->id,
            'first_name' => (string) $user->first_name,
            'last_name'  => (string) $user->last_name,
            'full_name'  => (string) $user->fullName(),
            'email'      => (string) $user->email,
            'account_id' => (int) $user->account_id,
            'locale'     => (string) $user->locale,
            'disabled'   => (bool) $user->disabled,
        ];
    }
}
