<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use ICF;

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

    protected $fillable = [
        'first_ip',
        'last_ip',
        'description',
        'contact_id',
        'enabled'
    ];

    protected $guarded  = [
        'id'
    ];

    // Relationships
    public function contact()
    {
        return $this->belongsTo('AbuseIO\Models\Contact');
    }

    // Accessors
    public function getFirstIpAttribute($value)
    {
        return ICF::inetItop($value);
    }

    public function getLastIpAttribute($value)
    {
        return ICF::inetItop($value);
    }

    // Mutators
    public function setFirstIpAttribute($value)
    {
        $this->attributes['first_ip'] = ICF::inetPtoi($value);
    }

    public function setLastIpAttribute($value)
    {
        $this->attributes['last_ip'] = ICF::inetPtoi($value);
    }
}
