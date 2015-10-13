<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 * @package AbuseIO\Models
 * @property string $name
 */
class Account extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $guarded  = [
        'id'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */


    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    function users()
    {
        return $this->hasMany('AbuseIO\Models\User');

    }
}
