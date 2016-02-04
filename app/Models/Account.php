<?php

namespace AbuseIO\Models;

use AbuseIO\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Log;

/**
 * Class Account
 * @package AbuseIO\Models
 * @property integer $id
 * @property string $name fillable
 * @property string $description fillable
 * @property int $brand_id fillable
 * @property boolean $disabled fillable
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 */
class Account extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'brand_id',
        'disabled',
        'systemaccount',
    ];

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created
     *
     * @return array $rules
     */
    public static function createRules()
    {
        $rules = [
            'name'          => 'required|unique:accounts',
            'brand_id'      => 'required|integer|exists:brands,id',
            'systemaccount' => 'sometimes|required|uniqueflag:accounts:systemaccount',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\Account $account
     * @return array $rules
     */
    public static function updateRules($account)
    {
        $rules = [
            'name'          => 'required|unique:accounts,name,'. $account->id,
            'brand_id'      => 'required|integer|exists:brands,id',
            'systemaccount' => 'sometimes|required|uniqueflag:accounts:systemaccount',
        ];

        return $rules;
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
    public function brands()
    {
        return $this->hasMany('AbuseIO\Models\Brand', 'creator_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('AbuseIO\Models\Ticket');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Accessor for the active brand
     *
     * @return Brand
     */
    public function getActiveBrandAttribute()
    {
        return $this->brand;
    }

    /**
     * Mutator for the active brand
     *
     * @param \AbuseIO\Models\Brand $brand
     */
    public function setActiveBrandAttribute(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Mutator for the active brand
     *
     * @param boolean $value
     */
    public function setSystemaccountAttribute($value)
    {
        if ($value) {
            $account = Account::where('systemaccount', true);
            $account->update(['systemaccount' => false]);
        }

        Account::where('id', $this->id)->update(['systemaccount' => $value]);
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Static method to check if the account has access to the model instance
     *
     * @param $model_id
     * @param $account
     * @return bool
     */
    public static function checkAccountAccess($model_id, $account)
    {
        // Early return when we are in the system account
        if ($account->isSystemAccount()) {
            return true;
        }

        $my_account = Account::find($model_id);

        $allowed = $my_account->account_id == $account->id;

        return ($allowed);
    }


    /**
     * Return if the account is the system account
     *
     * @return bool
     */
    public function isSystemAccount()
    {
        return ($this->systemaccount);
    }

    /**
     * Return the account that currently is the system account
     * If there is none, we die as its impossible to function without it
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \AbuseIO\Models\Account $account
     */
    public function scopeSystem($query)
    {
        $result =  $query->where('systemaccount', '=', 1);

        if (count($result) !== 1) {
            Log::error(
                'FindContact: ' .
                "FATAL ERROR - DEFAULT ACCOUNT (SYSTEMACCOUNT) MISSING"
            );
            dd();
        }
        return $result->first();
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
        if ($auth_account->isSystemAccount()) {
            return true;
        }

        // you can only edit your own account
        if ($auth_account->id == $this->id) {
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
        if ($this->isSystemAccount()) {
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

    public static function getSystemAccount()
    {
        return self::where("systemaccount", 1)->first();
    }
}
