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
        'brand_id',
        'disabled',
    ];

    protected $guarded  = [
        'id'
    ];


    /**
     * Return if the account is the system account
     *
     * @return bool
     */
    public function isSystemAccount()
    {
        return ($this->id == 1);
    }

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function brand()
    {
        return $this->belongsTo('AbuseIO\Models\Brand');
    }

}
