<?php

namespace AbuseIO\Models;

use AbuseIO\Traits\InstanceComparable;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

/**
 * Class Account.
 *
 * @property int    $id
 * @property string $name          fillable
 * @property string $description   fillable
 * @property int    $brand_id      fillable
 * @property bool   $disabled      fillable
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $deleted_at
 * @property bool   $systemaccount fillable
 * @property string $token'        fillable
 */
class Account extends Model
{
    use HasFactory;
    use SoftDeletes;
    use InstanceComparable;

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
        'token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created.
     *
     * @return array $rules
     */
    public static function createRules()
    {
        $rules = [
            'name'          => 'required|unique:accounts',
            'brand_id'      => 'required|integer|exists:brands,id',
            'systemaccount' => 'sometimes|required|uniqueflag:accounts:systemaccount',
            'disabled'      => 'required|stringorboolean', // disabled is sent as a string

        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated.
     *
     * @param \AbuseIO\Models\Account $account
     *
     * @return array $rules
     */
    public static function updateRules($account)
    {
        $rules = [
            'name'          => 'required|unique:accounts,name,'.$account->id,
            'brand_id'      => 'required|integer|exists:brands,id',
            'systemaccount' => 'sometimes|required|uniqueflag:accounts:systemaccount',
            'disabled'      => 'sometimes|required|stringorboolean', // disabled is sent as a string
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function brands()
    {
        return $this->hasMany(Brand::class, 'creator_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Brand::class);
    }

    /**
     * return the admins of an account.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function admins()
    {
        $admins = DB::table('users')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', '=', 'Admin')
            ->where('users.account_id', '=', $this->id)
            ->select('users.*')->get();

        return User::hydrate($admins);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Accessor for the active brand.
     *
     * @return Brand
     */
    public function getActiveBrandAttribute()
    {
        return $this->brand;
    }

    /**
     * Mutator/wrapper for the active brand.
     *
     * @param \AbuseIO\Models\Brand $brand
     */
    public function setActiveBrandAttribute(Brand $brand)
    {
        $this->brand = $brand;
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Static method to check if the account has access to the model instance.
     *
     * @param                         $model_id
     * @param \AbuseIO\Models\Account $account
     *
     * @return bool
     */
    public static function checkAccountAccess($model_id, self $account)
    {
        // Early return when we are in the system account
        if ($account->isSystemAccount()) {
            return true;
        }

        $my_account = self::find($model_id);

        return $my_account->is($account);
    }

    /**
     * Return if the account is the system account.
     *
     * @return bool
     */
    public function isSystemAccount()
    {
        return (bool) $this->systemaccount;
    }

    /**
     * Return the account that currently is the system account
     * If there is none, we die as its impossible to function without it.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \AbuseIO\Models\Account $account
     */
    public function scopeSystem($query)
    {
        $result = $query->where('systemaccount', '=', 1);

        if (count($result) !== 1) {
            Log::error(
                'FindContact: '.
                'FATAL ERROR - DEFAULT ACCOUNT (SYSTEMACCOUNT) MISSING'
            );
        }

        return $result->first();
    }

    /**
     * Checks if the current user may edit the account.
     *
     * @param User $user
     *
     * @return bool
     */
    public function mayEdit(User $user)
    {
        if ($user->account->isSystemAccount()) {
            return true;
        }

        // you can only edit your own account
        return $user->account->is($this);
    }

    /**
     * Checks if the current user may destroy the account
     * todo: currently use the mayEdit method to check.
     *
     * @param \AbuseIO\Models\User $user
     *
     * @return bool
     */
    public function mayDestroy(User $user)
    {
        return $this->mayEdit($user);
    }

    /**
     * Checks if the current user may disable the account.
     *
     * @param \AbuseIO\Models\User $user
     *
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
        return $auth_account->isSystemAccount();
    }

    /**
     * Check if the user may enable the account
     * (use the same logic as mayDisable() ).
     *
     * @param \AbuseIO\Models\User $user
     *
     * @return bool
     */
    public function mayEnable(User $user)
    {
        return $this->mayDisable($user);
    }

    /**
     * @return Account|null
     */
    public static function getSystemAccount()
    {
        return self::where('systemaccount', true)->first();
    }
}
