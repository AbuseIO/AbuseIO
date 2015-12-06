<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Account
 * @package AbuseIO\Models
 * @property string $name
 * @property string $description
 * @property int $brand_id
 * @property boolean $disabled
 * @property int $id
 */
class Account extends Model
{
    use SoftDeletes;

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

        // System user
        if ($auth_account->isSystemAccount())
        {
            return true;
        }

        // you can only edit your own account
        if ($auth_account->id == $this->id)
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


    /**
     * Checks if the current user may disable the account
     *
     * @param User $user
     * @return bool
     */
    public function mayDisable(User $user)
    {
        $auth_account = $user->account;

        // never disable the system account
        if ($this->isSystemAccount())
        {
            return false;
        }

        // only the system account may disable/enable accounts
        return ($auth_account->isSystemAccount());
    }


    /**
     * Check if the user may enable the account
     * (use the same logic as mayDisable() )
     *
     * @param User $user
     * @return bool
     */
    public function mayEnable(User $user)
    {
        return $this->mayDisable($user);
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
