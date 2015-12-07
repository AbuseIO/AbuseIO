<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * Validation rules for this model being created
     *
     * @param  Array $data
     * @return Array $rules
     */
    public function createRules($data)
    {
        $rules = [
            'name'              => 'required|string|min:1|unique:roles,name',
            'description'       => 'required|string|min:1',
        ];

        return $rules;
    }

    /*
     * Validation rules for this model being updated
     *
     * @param  Array $data
     * @return Array $rules
     */
    public function updateRules($data)
    {

        $rules = [
            'id'                => 'required|exists:roles,id',
            'name'              => 'required|string|min:1|unique:roles,name',
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
