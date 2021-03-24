<?php

namespace AbuseIO\Models;

use AbuseIO\Traits\InstanceComparable;
use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;

/**
 * Class User.
 *
 * @property int    $id
 * @property string $first_name     fillable
 * @property string $last_name      fillable
 * @property string $email          fillable
 * @property string $password       hidden
 * @property string $remember_token hidden
 * @property int    $account_id     fillable
 * @property string $locale         fillable
 * @property bool   $disabled       fillable
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $deleted_at
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use SoftDeletes;
    use InstanceComparable;
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'account_id',
        'locale',
        'disabled',
        'options',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'options',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created.
     *
     * @return array
     */
    public static function createRules()
    {
        $rules = [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|confirmed|min:6|max:32',
            'account_id' => 'required|integer|exists:accounts,id',
            'locale'     => 'required|min:2|max:3',
            'disabled'   => 'required|stringorboolean', // disabled is sent as a string
            'roles'      => 'sometimes',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated.
     *
     * @param \AbuseIO\Models\User $user
     *
     * @return array
     */
    public static function updateRules($user)
    {
        $rules = [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|email|unique:users,email,'.$user->id,
            'password'   => 'sometimes|confirmed|min:6|max:32',
            'account_id' => 'required|integer|exists:accounts,id',
            'locale'     => 'sometimes|required|min:2|max:3',
            'disabled'   => 'sometimes|required|stringorboolean', // disabled is sent as a string
            'roles'      => 'sometimes',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Many-To-Many Relationship Method for accessing the User->roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * One-To-Many relation to account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACL Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Checks a Permission.
     *
     * @param string $permission Permission Slug of a permission (i.e: manage_user)
     *
     * @return bool
     */
    public function cando($permission = null)
    {
        return !is_null($permission) && $this->checkPermission($permission);
    }

    /**
     * Check if the permission matches with any permission user has.
     *
     * @param string $perm Permission name of a permission
     *
     * @return bool
     */
    protected function checkPermission($perm)
    {
        $permissions = $this->getAllPermissionsFromAllRoles();

        $permissionArray = is_array($perm) ? $perm : [$perm];

        return count(array_intersect($permissions, $permissionArray));
    }

    /**
     * Get all permission names from all permissions of all roles.
     *
     * @return array
     */
    protected function getAllPermissionsFromAllRoles()
    {
        $permissions =
            $this->roles
            ->load('permissions')
            ->pluck('permissions')
            ->toArray();

        return array_map(
            'strtolower',
            array_unique(
                Arr::flatten(
                    array_map(
                        function ($permission) {
                            return Arr::pluck($permission, 'name');
                        },
                        $permissions
                    )
                )
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Encrypt password to hash.
     *
     * @param $value String The password to set
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Static method to check if the account has access to the model instance.
     *
     * @param int                     $model_id
     * @param \AbuseIO\Models\Account $account
     *
     * @return bool
     */
    public static function checkAccountAccess($model_id, Account $account)
    {
        // Early return when we are in the system account
        if ($account->isSystemAccount()) {
            return true;
        }

        $user = self::find($model_id);

        return $user->account->is($account);
    }

    /**
     * Return the fullname of the user.
     *
     * @return string
     */
    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if the current user is allowed to login.
     *
     * @param array &$messages Array of messages
     *
     * @return bool
     */
    public function mayLogin(&$messages)
    {
        $account = $this->account;
        // First user is always allowed to login, early return
        if ($account->isSystemAccount()) {
            return true;
        }

        $result = true;

        // Check if the account is disabled (system account is never disabled)

        if ($account->disabled) {
            array_push($messages, "The account {$account->name} for this login is disabled.");
            $result = false;
        }

        if ($this->disabled) {
            array_push($messages, trans('users.login_disabled'));
            $result = false;
        }

        return $result;
    }

    /**
     * Checks if the user has a specific role.
     *
     * @param string $role_name Name of the role
     *
     * @return bool
     */
    public function hasRole($role_name)
    {
        $result = false;
        $roles = $this->roles;
        foreach ($roles as $role) {
            if ($role->name == ucfirst($role_name)) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Check to see if we can disable the user.
     *
     * @param \AbuseIO\Models\User $auth_user The User Model
     *
     * @return bool
     */
    public function mayDisable(self $auth_user)
    {
        // can't disable/enable ourselves
        return !$auth_user->is($this);
    }

    /**
     * Check to see if we can enable the user
     * (using the logic in mayDisable()).
     *
     * @param \AbuseIO\Models\User $auth_user The User Model
     *
     * @return bool
     */
    public function mayEnable(self $auth_user)
    {
        return $this->mayDisable($auth_user);
    }

    /**
     * Save an option in the user.
     *
     * @param $name
     * @param $value
     */
    public function setOption($name, $value)
    {
        // can't access the array directly so retrieve it,
        // edit it and write it back.

        $options = $this->options;
        $options[$name] = $value;
        $this->options = $options;

        $this->save();
    }

    /**
     * Retrieve an option from the user.
     *
     * @param $name
     *
     * @return null
     */
    public function getOption($name)
    {
        $result = null;

        $options = $this->options;
        if (is_array($options) && array_key_exists($name, $options)) {
            $result = $options[$name];
        }

        return $result;
    }
}
