<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * @package AbuseIO\Models
 */
class Job extends Model
{
    /**
     * @var string
     */
    protected $table    = 'jobs';

    /**
     * @var array
     */
    protected $fillable = [
        //
    ];

    /**
     * @var array
     */
    protected $guarded  = [
        'id',
        'queue',
        'payload',
        'attempts',
        'reserved',
        'reserved_at',
        'available_at',
        'created_at',
    ];
}
