<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

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

    protected $table    = 'events';

    protected $fillable =
        [
            'ticket_id',
            'evidence_id',
            'source',
            'timestamp',
            'information'
        ];

    protected $guarded  = ['id'];

    public function evidences()
    {

        return $this->hasMany('AbuseIO\Models\Evidence', 'id', 'evidence_id');

    }
}
