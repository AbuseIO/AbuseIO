<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Note
 * @package AbuseIO\Models
 * @property integer $id
 * @property integer $ticket_id fillable
 * @property string $submitter fillable
 * @property string $text fillable
 * @property boolean $hidden fillable
 * @property boolean $viewed fillable
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
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
     * Validation rules for this model being created
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
     * Validation rules for this model being updated
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
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Updates the updated at before passing it along
     *
     * @param  string $date
     * @return bool|string
     */
    public function getUpdatedAtAttribute($date)
    {
        return date(config('app.date_format').' '.config('app.time_format'), strtotime($date));
    }

    /**
     * Updates the created at before passing it along
     *
     * @param string $date
     * @return bool|string
     */
    public function getCreatedAtAttribute($date)
    {
        return date(config('app.date_format').' '.config('app.time_format'), strtotime($date));
    }
}
