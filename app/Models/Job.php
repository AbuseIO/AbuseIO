<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * @package AbuseIO\Models
 * @property integer $id
 * @property string $queue
 * @property string $payload
 * @property integer $attempts
 * @property integer $reserved
 * @property integer $reserved_at
 * @property integer $available_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 */
class Job extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //
    ];
}
