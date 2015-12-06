<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRole extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions_role';

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
     * The default model validation rules on creation
     */
    private $createRules = [
        'role_id'               => 'required|integer',
        'permission_id'         => 'required|integer',
    ];

    /*
     * The default model validation rules on update
     */
    private $updateRules = [
        'id'                    => 'required|exists:permissions_role,id',
        'role_id'               => 'required|integer',
        'permission_id'         => 'required|integer',
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
