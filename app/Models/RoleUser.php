<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RoleUser.
 *
 * @property int $id         guarded
 * @property int $role_id
 * @property int $user_id
 * @property int $created_at guarded
 * @property int $updated_at guarded
 * @property int $deleted_at guarded
 */
class RoleUser extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'user_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created.
     *
     * @param \AbuseIO\Models\RoleUser $roleUser
     *
     * @return array $rules
     */
    public static function createRules($roleUser)
    {
        $rules = [
            'role_id' => 'required|integer|exists:roles,id|'.
                         'unique:role_user,role_id,NULL,id,user_id,'.$roleUser->user_id,
            'user_id' => 'required|integer|exists:users,id|'.
                         'unique:role_user,user_id,NULL,id,role_id,'.$roleUser->role_id,
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated.
     *
     * @param \AbuseIO\Models\RoleUser $roleUser
     *
     * @return array $rules
     */
    public static function updateRules($roleUser)
    {
        $rules = [
            'id'      => 'required|exists:permissions_role,id',
            'role_id' => 'required|integer|exists:roles,id|'.
                         'unique:role_user,role_id,NULL,id,user_id,'.$roleUser->user_id,
            'user_id' => 'required|integer|exists:users,id|'.
                         'unique:role_user,user_id,NULL,id,role_id,'.$roleUser->role_id,
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * One-To-Many relation to account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * One-To-Many relation to account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
