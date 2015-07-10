<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Netblock
 * @package AbuseIO\Models
 * @property string $first_ip
 * @property string $last_ip
 * @property string $description
 * @property integer $contact_id
 * @property boolean $enabled
 */
class Netblock extends Model
{

    protected $table    = 'netblocks';

    protected $fillable =
        [
            'first_ip',
            'last_ip',
            'description',
            'contact_id',
            'enabled'
        ];

    protected $guarded  = ['id'];

    public function contact()
    {

        return $this->belongsTo('AbuseIO\Models\Contact');

    }
}
