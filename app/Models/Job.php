<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * @package AbuseIO\Models
 * @property integer $id guarded
 * @property string $queue guarded
 * @property string $payload guarded
 * @property integer $attempts guarded
 * @property integer $reserved guarded
 * @property integer $reserved_at guarded
 * @property integer $available_at guarded
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
 */
class Job extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table    = 'jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //
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
        'queue',
        'payload',
        'attempts',
        'reserved',
        'reserved_at',
        'available_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
