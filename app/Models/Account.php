<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 * @package AbuseIO\Models
 * @property string $name
 */
class Account extends Model
{

    protected $table    = 'accounts';

    protected $fillable = [
        'name',
        'description',
        'brand_id',
        'disabled',
    ];

    protected $guarded  = [
        'id'
    ];


    /**
     * Return if the account is the system account
     *
     * @return bool
     */
    public function isSystemAccount()
    {
        return ($this->id == 1);
    }

    /**
     * Checks if the current user may edit the account
     *
     * @param User $user
     * @return bool
     */
    public function mayEdit(User $user)
    {
        $auth_account = $user->account;

        // System admin may always edit
        if ($auth_account->isSystemAccount() && $user->hasRole('admin'))
        {
            return true;
        }

        if ($auth_account->id == $this->id && $user->hasRole('admin'))
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if the current user may destroy the account
     * todo: currently use the mayEdit method to check
     *
     * @param User $user
     * @return bool
     */
    public function mayDestroy(User $user)
    {
        return $this->mayEdit($user);
    }



    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('AbuseIO\Models\User');

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany('AbuseIO\Models\Contact');

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function brand()
    {
        return $this->belongsTo('AbuseIO\Models\Brand');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('AbuseIO\Models\Ticket');
    }
}
