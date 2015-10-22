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

    protected $table    = 'accounts';

    protected $fillable = [
        'name',
        'description',
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
    public function users()
    {
        return $this->hasMany('AbuseIO\Models\User');

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany('AbuseIO\Models\Contact');

    }

}
