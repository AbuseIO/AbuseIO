<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    use SoftDeletes;

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

    /*
     * Validation rules for this model being created
     *
     * @param  Model $netblock
     * @return Array $rules
     */
    public function createRules($netblock)
    {
        $rules = [
            'first_ip'      => "required|ip|unique:netblocks,first_ip,NULL,id,last_ip,{$netblock->last_ip}",
            'last_ip'       => "required|ip|unique:netblocks,last_ip,NULL,id,first_ip,{$netblock->first_ip}",
            'contact_id'    => 'required|integer',
            'description'   => 'required',
            'enabled'       => 'required|boolean',
        ];

        return $rules;
    }

    /*
     * Validation rules for this model being updated
     *
     * @param  Model $netblock
     * @return Array $rules
     */
    public function updateRules($netblock)
    {
        $rules = [
            'first_ip'      => "required|ip|unique:netblocks,first_ip,{$netblock->id},id,last_ip,{$netblock->last_ip}",
            'last_ip'       => "required|ip|unique:netblocks,last_ip,{$netblock->id},id,first_ip,{$netblock->first_ip}",
            'contact_id'    => 'required|integer',
            'description'   => 'required',
            'enabled'       => 'required|boolean',
        ];

        return $rules;
    }

    // Relationships
    public function contact()
    {
        return $this->belongsTo('AbuseIO\Models\Contact');
    }

    // Mutators
    public function setFirstIpAttribute($value)
    {
        $this->attributes['first_ip'] = $value;
        $this->attributes['first_ip_int'] = ICF::InetPtoi($value);
    }

    public function setLastIpAttribute($value)
    {
        $this->attributes['last_ip'] = $value;
        $this->attributes['last_ip_int'] = ICF::InetPtoi($value);
    }
}
