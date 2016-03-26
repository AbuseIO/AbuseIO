<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * @package AbuseIO\Models
 * @property integer $id
 * @property integer $connection
 * @property string $queue
 * @property string $payload
 * @property integer $failed_at
 */
class FailedJob extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'failed_jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //
    ];
}
