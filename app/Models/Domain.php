<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Domain
 * @package AbuseIO\Models
 * @property string $name
 * @property integer $contact_id
 * @property boolean $enabled
 */
class Domain extends Model
{

    protected $table    = 'domains';
    protected $fillable = [
        'name',
        'contact_id',
        'enabled'
    ];
    protected $guarded  = ['id'];

    public function contact() {
        return $this->belongsTo('AbuseIO\Models\Contact');
    }

}
