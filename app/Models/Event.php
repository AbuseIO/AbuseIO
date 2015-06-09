<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

    protected $table    = 'events';
    protected $fillable = [
        'ticket_id',
        'evidence_id',
        'source',
        'timestamp',
        'information'
    ];
    protected $guarded  = ['id'];

    public function ticket() {
        return $this->belongsTo('AbuseIO\Models\Ticket');
    }

}
