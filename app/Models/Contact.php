<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {

    protected $table    = 'contacts';
    protected $fillable = [
        'reference',
        'name',
        'email',
        'rpc_host',
        'rpc_key',
        'auto_notify',
        'enabled'
    ];
    protected $guarded  = ['id'];

    public function shortlist() {
        return $this->belongsTo('id', 'name');
    }

}
