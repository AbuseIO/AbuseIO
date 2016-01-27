<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Evidence.
 * @package AbuseIO\Models
 * @property int $id guarded
 * @property string $filename
 * @property string $sender
 * @property string $subject
 * @property int $created_at guarded
 * @property int $updated_at guarded
 * @property int $deleted_at guarded
 */
class Evidence extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'evidences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'sender',
        'subject',
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
        $rules = [
            'filename'  => 'required|file',
            'sender'    => 'required|string',
            'subject'   => 'required|string',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the event for this evidence.
     *
     * TODO: remove this method when relation are fixed
     * https://abuseio.myjetbrains.com/youtrack/issue/AIO-77
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('AbuseIO\Models\Event');
    }

    /**
     * Returns the events for this evidence.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany('AbuseIO\Models\Event');
    }
}
