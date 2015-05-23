<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class events extends Model {

    protected $table    = 'events';
    protected $fillable = [
        'ticket_id',
        'evidence_id',
        'source',
        'uri',
        'timestamp',
        'information'
    ];
    protected $guarded  = ['id'];

    public function ticket() {
        return $this->BelongsTo('AbuseIO\Models\ticket');
    }
}
