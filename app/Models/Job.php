<?php

namespace AbuseIO\Models;

use Database\Factories\JobFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
#[UseFactory(JobFactory::class)]
class Job extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jobs';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //
    ];
}
