<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class Evidence extends Model {

    protected $table    = 'evidences';
    protected $fillable = [
        'data',
        'sender',
        'subject'
    ];
    protected $guarded  = ['id'];

    public function event() {
        return $this->BelongsTo('AbuseIO\Models\event');
    }
    
}
