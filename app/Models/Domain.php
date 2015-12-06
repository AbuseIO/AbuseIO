<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Domain
 * @package AbuseIO\Models
 * @property string $name
 * @property integer $contact_id
 * @property boolean $enabled
 */
class Domain extends Model
{
    use SoftDeletes;

    protected $table    = 'domains';

    protected $fillable = [
        'name',
        'contact_id',
        'enabled'
    ];

    protected $guarded  = [
        'id'
    ];

    public function contact()
    {

        return $this->belongsTo('AbuseIO\Models\Contact');

    }
}
