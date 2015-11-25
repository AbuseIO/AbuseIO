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

    protected $appends = [
        'first_ip_int',
        'last_ip_int'
    ];

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
    public function getFirstIpIntAttribute()
    {
        return ICF::InetPtoi($this->first_ip);
    }

    public function getLastIpIntAttribute()
    {
        return ICF::InetPtoi($this->last_ip);
    }
}
