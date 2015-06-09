<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class Netblock extends Model {

    protected $table    = 'netblocks';
    protected $fillable = [
        'first_ip',
        'last_ip',
        'description',
        'contact_id',
        'enabled'
    ];
    protected $guarded  = ['id'];

    public function contact() {
        return $this->belongsTo('AbuseIO\Models\Contact');
    }

}
