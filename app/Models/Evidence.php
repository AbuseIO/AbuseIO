<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Evidence
 * @package AbuseIO\Models
 * @property string $filename
 * @property string $sender
 * @property string $subject
 */
class Evidence extends Model
{
    use SoftDeletes;

    protected $table    = 'evidences';

    protected $fillable = [
        'filename',
        'sender',
        'subject'
    ];

    protected $guarded  = [
        'id'
    ];

    /*
     * Validation rules for this model being created
     *
     * @param  \AbuseIO\Models\Evidence $evidence
     * @return array $rules
     */
    public function createRules($evidence)
    {
        $rules = [
            // TODO: Create validation rules instead of EventValidator
        ];

        return $rules;
    }

    /*
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\Evidence $evidence
     * @return array $rules
     */
    public function updateRules($evidence)
    {
        $rules = [
            // TODO: Create validation rules instead of EventValidator
        ];

        return $rules;
    }

    public function event()
    {

        return $this->belongsTo('AbuseIO\Models\event');

    }
}
