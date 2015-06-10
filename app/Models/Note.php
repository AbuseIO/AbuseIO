<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

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

    protected $table    = 'notes';
    protected $fillable = [
        'ticket_id',
        'submitter',
        'text',
        'hidden',
        'viewed',
    ];
    protected $guarded  = ['id'];

    public function ticket() {
        return $this->belongsTo('AbuseIO\Models\Ticket');
    }

}
