<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Note
 * @package AbuseIO\Models
 * @property integer $ticket_id
 * @property string $submitter
 * @property string $text
 * @property boolean $hidden
 * @property boolean $viewed
 */
class Note extends Model
{

    use SoftDeletes;

    protected $table    = 'notes';

    protected $fillable = [
        'ticket_id',
        'submitter',
        'text',
        'hidden',
        'viewed',
    ];

    protected $guarded  = [
        'id'
    ];

    /**
     * Validation rules for this model being created
     *
     * @param  \AbuseIO\Models\Note $note
     * @return array $rules
     */
    public static function createRules(/** @noinspection PhpUnusedParameterInspection */$note)
    {
        $rules = [
            'ticket_id' => 'required|integer',
            'submitter' => 'required|string',
            'text'      => 'required|string',
            'hidden'    => 'sometimes|boolean',
            'viewed'    => 'sometimes|boolean',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\Note $note
     * @return array $rules
     */
    public static function updateRules(/** @noinspection PhpUnusedParameterInspection */ $note)
    {
        $rules = [
            'ticket_id' => 'sometimes|integer',
            'submitter' => 'required|string',
            'hidden' => 'sometimes|boolean',
            'viewed' => 'sometimes|boolean',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Updates the updated at before passing it along
     *
     * @param $date
     * @return bool|string
     */
    public function getUpdatedAtAttribute($date)
    {
        return date(config('app.date_format').' '.config('app.time_format'), strtotime($date));
    }

    /**
     * Updates the created at before passing it along
     *
     * @param $date
     * @return bool|string
     */
    public function getCreatedAtAttribute($date)
    {
        return date(config('app.date_format').' '.config('app.time_format'), strtotime($date));
    }
}
