<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 * @package AbuseIO\Models
 * @property integer $id guarded
 * @property string $name
 * @property string $description
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
 */
class Role extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
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
     * @param  \AbuseIO\Models\Role $role
     * @return array $rules
     */
    public static function createRules(/** @noinspection PhpUnusedParameterInspection */ $role)
    {
        $rules = [
            'name'              => 'required|string|min:1|unique:roles,name',
            'description'       => 'required|string|min:1',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\Role $role
     * @return array $rules
     */
    public static function updateRules($role)
    {

        $rules = [
            'id'                => 'required|exists:roles,id',
            'name'              => 'required|string|min:1|unique:roles,name,' . $role->id,
            'description'       => 'required|string|min:1',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * many-to-many relationship method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('AbuseIO\Models\User');
    }

    /**
     * many-to-many relationship method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany('AbuseIO\Models\Permission');
    }
}
