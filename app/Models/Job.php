<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event.
 *
 * @property int    $id
 * @property string $queue
 * @property string $payload
 * @property int    $attempts
 * @property int    $reserved
 * @property int    $reserved_at
 * @property int    $available_at
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $deleted_at
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
