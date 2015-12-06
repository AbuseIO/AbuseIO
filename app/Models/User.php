<?php namespace AbuseIO\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;
use Validator;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, SoftDeletes;

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
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /*
     * The default model validation rules on creation
     */
    private $createRules = [
        'first_name'    => 'required|string',
        'last_name'     => 'required|string',
        'email'         => 'required|email|unique:users,email',
        'password'      => 'required|string|min:6|max:32',
        'account_id'    => 'required|integer',
        'locale'        => 'required|min:2|max:3',
        'disabled'      => 'required:boolean',
    ];

    /*
     * The default model validation rules on update
     */
    private $updateRules = [
        'first_name'    => 'required|string',
        'last_name'     => 'required|string',
        'email'         => 'required|email|exists:users,email',
        'password'      => 'string|min:6|max:32', //protect field only availabe if being updated
        'account_id'    => 'required|integer',
        'locale'        => 'required|min:2|max:3',
        'disabled'      => 'required|boolean',
    ];

    /*
     * Validation method for this model being created
     */
    public function validateCreate($data)
    {
        $validation = Validator::make($data, $this->createRules);

        return $validation;
    }

    /*
     * Validation method for this model being updated
     */
    public function validateUpdate($data)
    {
        $validation = Validator::make($data, $this->updateRules);

        return $validation;
    }


    /*
    |--------------------------------------------------------------------------
    | ACL Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Checks a Permission
     *
     * @param  String permission Slug of a permission (i.e: manage_user)
     * @return Boolean true if has permission, otherwise false
     */
    public function can($permission = null)
    {
        return !is_null($permission) && $this->checkPermission($permission);
    }

    /**
     * Check if the permission matches with any permission user has
     *
     * @param  String permission name of a permission
     * @return Boolean true if permission exists, otherwise false
     */
    protected function checkPermission($perm)
    {
        $permissions = $this->getAllPermissionsFromAllRoles();

        $permissionArray = is_array($perm) ? $perm : [$perm];

        return count(array_intersect($permissions, $permissionArray));
    }

    /**
     * Get all permission names from all permissions of all roles
     *
     * @return Array of permission names
     */
    protected function getAllPermissionsFromAllRoles()
    {
        $permissions = $this->roles->load('permissions')->fetch('permissions')->toArray();

        return array_map(
            'strtolower',
            array_unique(
                array_flatten(
                    array_map(
                        function ($permission) {

                            return array_fetch($permission, 'permission_slug');

                        },
                        $permissions
                    )
                )
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Methods
    |--------------------------------------------------------------------------
    */

    /**
     * return the fullname of the user
     *
     * @return string
     */
    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Check if the current user is allowed to login
     *
     * @param $messages
     * @return bool
     */
    public function mayLogin(&$messages)
    {
        // first user is always allowed to login, early return
        if ($this->id == 1) {
            return true;
        }

        $result = true;

        // check if the account is disabled (system account is never disabled)
        $account = $this->account;
        if ($account->disabled && $account->id != 1) {
            array_push($messages, 'The account "' . $account->name . '" for this login is disabled.');
            if ($result) {
                $result = false;
            }
        }

        if ($this->disabled) {
            array_push($messages, 'This login is disabled.');
            if ($result) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Checks if the user has a specific role
     *
     * @param $role_name
     * @return bool
     */
    public function hasRole($role_name)
    {
        $result = false;
        $roles = $this->roles;
        foreach ($roles as $role)
        {
            if ($role->role_name == $role_name)
            {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Check to see if we can disable the user
     *
     * @param User $auth_user
     * @return bool
     */
    public function mayDisable(User $auth_user)
    {
        $account = $this->account;
        $auth_account = $auth_user->account;

        // can't disable/enable ourselves
        if ($auth_user->id == $this->id)
        {
            return false;
        }

        // can only enable/disable users from our own account, except for the systemaccount
        if ($auth_account->id == $account->id || $auth_account->isSystemAccount())
        {
            return true;
        }

        // all other cases
        return false;
    }

    /**
     * Check to see if we can enable the user
     * (using the logic in mayDisable())
     *
     * @param User $auth_user
     * @return bool
     */
    public function mayEnable(User $auth_user)
    {
        return $this->mayDisable($auth_user);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Many-To-Many Relationship Method for accessing the User->roles
     *
     * @return QueryBuilder Object
     */
    public function roles()
    {
        return $this->belongsToMany('AbuseIO\Models\Role');
    }

    /**
     * One-To-Many relation to account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('AbuseIO\Models\Account');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
