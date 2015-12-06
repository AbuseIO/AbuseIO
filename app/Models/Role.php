<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

/**
 * Class Role
 * @package AbuseIO\Models
 * @property integer $id
 * @property string $name
 * @property string $description
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

    /*
     * Validation method for this model being created
     * $rules is inside function because of interaction with $data
     */
    public function validateCreate($data)
    {
        $rules = [
            'name'              => 'required|string|min:1|unique:roles,name',
            'description'       => 'required|string|min:1',
        ];

        $validation = Validator::make($data, $rules);

        return $validation;
    }

    /*
     * Validation method for this model being updated
     * $rules is inside function because of interaction with $data
     */
    public function validateUpdate($data)
    {

        $rules = [
            'id'                => 'required|exists:roles,id',
            'name'              => 'required|string|min:1|unique:roles,name',
            'description'       => 'required|string|min:1',
        ];

        $validation = Validator::make($data, $rules);

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
