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

    /**
     * Validation rules for this model being created
     *
     * @param  \AbuseIO\Models\Evidence $evidence
     * @return array $rules
     */
    public static function createRules(/** @noinspection PhpUnusedParameterInspection */ $evidence)
    {
        $rules = [
            'filename'          => 'required|file',
            'sender'            => 'required|string',
            'subject'           => 'required|string',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\Evidence $evidence
     * @return array $rules
     */
    public static function updateRules(/** @noinspection PhpUnusedParameterInspection */ $evidence)
    {
        $rules = [
            'filename'          => 'required|file',
            'sender'            => 'required|string',
            'subject'           => 'required|string',
        ];

        return $rules;
    }

    /**
     * Returns the event for this evidence
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {

        return $this->belongsTo('AbuseIO\Models\event');

    }
}
