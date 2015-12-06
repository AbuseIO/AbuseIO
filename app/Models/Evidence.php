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

    public function event()
    {

        return $this->belongsTo('AbuseIO\Models\event');

    }
}
