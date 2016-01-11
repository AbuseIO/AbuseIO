<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RoleUser
 * @package AbuseIO\Models
 * @property integer $id guarded
 * @property integer $role_id
 * @property integer $user_id
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
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
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * Validation rules for this model being created
     *
     * @param  \AbuseIO\Models\RoleUser $roleUser
     * @return array $rules
     */
    public static function createRules($roleUser)
    {
        $rules = [
            'role_id' => 'required|integer|unique:role_user,role_id,NULL,id,user_id,' . $roleUser->user_id,
            'user_id' => 'required|integer|unique:role_user,user_id,NULL,id,role_id,' . $roleUser->role_id,
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\RoleUser $roleUser
     * @return array $rules
     */
    public static function updateRules($roleUser)
    {
        $rules = [
            'id'      => 'required|exists:permissions_role,id',
            'role_id' => 'required|integer|unique:role_user,role_id,NULL,id,user_id,' . $roleUser->user_id,
            'user_id' => 'required|integer|unique:role_user,user_id,NULL,id,role_id,' . $roleUser->role_id,
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
    public function user()
    {
        return $this->belongsTo('AbuseIO\Models\User');
    }
}
