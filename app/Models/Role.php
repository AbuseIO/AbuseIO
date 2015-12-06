<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

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
        'role_name',
        'role_description',
    ];

    /*
     * The default model validation rules on creation
     */
    private $createRules = [
        'role_name'         => 'required|string|min:1|unique:roles,role_name',
        'role_description'  => 'required|string|min:1',
    ];

    /*
     * The default model validation rules on update
     */
    private $updateRules = [
        'id'                => 'required|exists:roles,id',
        'role_name'         => 'required|string|min:1|unique:roles,role_name',
        'role_description'  => 'required|string|min:1',
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
     * many-to-many relationship method.
     *
     * @return QueryBuilder
     */
    public function users()
    {
        return $this->belongsToMany('AbuseIO\Models\User');
    }

    /**
     * many-to-many relationship method.
     *
     * @return QueryBuilder
     */
    public function permissions()
    {
        return $this->belongsToMany('AbuseIO\Models\Permission');
    }
}
