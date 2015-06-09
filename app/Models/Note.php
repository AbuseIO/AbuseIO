<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model {

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
