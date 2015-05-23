<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model {

    protected $table    = 'domains';
    protected $fillable = [
        'name',
        'contact_id',
        'enabled'
    ];
    protected $guarded  = ['id'];

    public function contact() {
        return $this->BelongsTo('AbuseIO\Models\contact');
    }

}
