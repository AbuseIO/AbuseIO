<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Note
 * @package AbuseIO\Models
 * @property integer $id guarded
 * @property integer $ticket_id
 * @property string $submitter
 * @property string $text
 * @property boolean $hidden
 * @property boolean $viewed
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
 */
class Note extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table    = 'notes';

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
        'created_at',
        'updated_at',
        'deleted_at',
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
