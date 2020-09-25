<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event.
 *
 * @property int    $id
 * @property int    $connection
 * @property string $queue
 * @property string $payload
 * @property int    $failed_at
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
