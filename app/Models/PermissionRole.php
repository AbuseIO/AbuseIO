<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PermissionRole.
 *
 * @property int $id
 * @property int $role_id       fillable
 * @property int $permission_id fillable
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 */
class PermissionRole extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permission_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created.
     *
     * @param \AbuseIO\Models\PermissionRole $permissionRole
     *
     * @return array $rules
     */
    public static function createRules($permissionRole)
    {
        $rules = [
            'role_id' => 'required|integer|exists:roles,id|'.
                               'unique:permission_role,role_id,NULL,id,permission_id,'.$permissionRole->permission_id,
            'permission_id' => 'required|integer|exists:permissions,id|'.
                               'unique:permission_role,permission_id,NULL,id,role_id,'.$permissionRole->role_id,
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated.
     *
     * @param \AbuseIO\Models\PermissionRole $permissionRole
     *
     * @return array $rules
     */
    public static function updateRules($permissionRole)
    {
        $rules = [
            'id'      => 'required|exists:permissions_role,id',
            'role_id' => 'required|integer|exists:roles,id|'.
                               'unique:permission_role,role_id,NULL,id,permission_id,'.$permissionRole->permission_id,
            'permission_id' => 'required|integer|exists:permissions,id|'.
                               'unique:permission_role,permission_id,NULL,id,role_id,'.$permissionRole->role_id,
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
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
