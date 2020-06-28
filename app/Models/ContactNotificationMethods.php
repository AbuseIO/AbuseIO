<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class ContactNotificationMethods extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'method',
    ];

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created.
     *
     * @return array $rules
     */
    public static function createRules()
    {
        return [
            'method' => 'required|string',
        ];
    }

    /**
     * Validation rules for this model being updated.
     *
     * @return array $rules
     */
    public static function updateRules()
    {
        return static::createRules();
    }

    /*
     |--------------------------------------------------------------------------
     | Relationship Methods
     |--------------------------------------------------------------------------
     */

    /**
     * Returns the contact to whom this notification method belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
