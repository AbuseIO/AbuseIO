<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PermissionRole
 * @package AbuseIO\Models
 * @property integer $id guarded
 * @property integer $role_id
 * @property integer $permission_id
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
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

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that cannot be changed
     *
     * @var array
     */
    protected $guarded  = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Validation rules for this model being created
     *
     * @param  \AbuseIO\Models\PermissionRole $permissionRole
     * @return array $rules
     */
    public static function createRules($permissionRole)
    {
        $rules = [
            'role_id'               => 'required|integer|' .
                'unique:permission_role,role_id,NULL,id,permission_id,' . $permissionRole->permission_id,
            'permission_id'         => 'required|integer|' .
                'unique:permission_role,permission_id,NULL,id,role_id,' . $permissionRole->role_id,
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\PermissionRole $permissionRole
     * @return array $rules
     */
    public static function updateRules($permissionRole)
    {
        $rules = [
            'id'                    => 'required|exists:permissions_role,id',
            'role_id'               => 'required|integer|' .
                'unique:permission_role,role_id,NULL,id,permission_id,' . $permissionRole->permission_id,
            'permission_id'         => 'required|integer|' .
                'unique:permission_role,permission_id,NULL,id,role_id,' . $permissionRole->role_id,
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * One-To-Many relation to account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('AbuseIO\Models\Role');
    }

    /**
     * One-To-Many relation to account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission()
    {
        return $this->belongsTo('AbuseIO\Models\Permission');
    }
}
