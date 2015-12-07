<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event
 * @package AbuseIO\Models
 * @property integer $ticket_id
 * @property integer $evidence_id
 * @property string $source
 * @property integer $timestamp
 * @property string $information
 */
class Event extends Model
{
    use SoftDeletes;

    protected $table    = 'events';

    protected $fillable = [
        'ticket_id',
        'evidence_id',
        'source',
        'timestamp',
        'information'
    ];

    protected $guarded  = [
        'id'
    ];

    /*
     * Validation rules for this model being created
     *
     * @param  Model $event
     * @return Array $rules
     */
    public function createRules($event)
    {
        $rules = [
            // TODO: Create validation rules instead of EventValidator
        ];

        return $rules;
    }

    /*
     * Validation rules for this model being updated
     *
     * @param  Model $event
     * @return Array $rules
     */
    public function updateRules($event)
    {
        $rules = [
            // TODO: Create validation rules instead of EventValidator
        ];

        return $rules;
    }

    public function evidences()
    {

        return $this->hasMany('AbuseIO\Models\Evidence', 'id', 'evidence_id');

    }
}
