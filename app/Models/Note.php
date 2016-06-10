<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Note.
 *
 * @property int $id
 * @property int $ticket_id fillable
 * @property string $submitter fillable
 * @property string $text fillable
 * @property bool $hidden fillable
 * @property bool $viewed fillable
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 */
class Note extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'submitter',
        'text',
        'hidden',
        'viewed',
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
            'ticket_id' => 'required|integer|exists:tickets,id',
            'submitter' => 'required|string',
            'text'      => 'required|string',
            'hidden'    => 'sometimes|boolean',
            'viewed'    => 'sometimes|boolean',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated.
     *
     * @return array $rules
     */
    public static function updateRules()
    {
        $rules = [
            'ticket_id' => 'required|integer|exists:tickets,id',
            'submitter' => 'required|string',
            'hidden'    => 'sometimes|boolean',
            'viewed'    => 'sometimes|boolean',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo('AbuseIO\Models\Ticket');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Updates the updated at before passing it along.
     *
     * @param string $date
     *
     * @return bool|string
     */
    public function getUpdatedAtAttribute($date)
    {
        return date(config('app.date_format').' '.config('app.time_format'), strtotime($date));
    }

    /**
     * Updates the created at before passing it along.
     *
     * @param string $date
     *
     * @return bool|string
     */
    public function getCreatedAtAttribute($date)
    {
        return date(config('app.date_format').' '.config('app.time_format'), strtotime($date));
    }
}
