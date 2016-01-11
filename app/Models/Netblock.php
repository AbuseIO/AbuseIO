<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ICF;

/**
 * Class Netblock
 * @package AbuseIO\Models
 * @property integer $id guarded
 * @property string $first_ip
 * @property string $last_ip
 * @property string $description
 * @property integer $contact_id
 * @property boolean $enabled
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
 */
class Netblock extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table    = 'netblocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_ip',
        'last_ip',
        'description',
        'contact_id',
        'enabled'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that cannot be changed
     *
     * @var array
     */
    protected $guarded  = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Validation rules for this model being created
     *
     * @param  \AbuseIO\Models\Netblock $netblock
     * @return array $rules
     */
    public static function createRules($netblock)
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

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\Netblock $netblock
     * @return array $rules
     */
    public static function updateRules($netblock)
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

    /**
     * Returns the contact for this netblock
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo('AbuseIO\Models\Contact');
    }

    // Mutators

    /**
     * Updates the first IP attribute before giving it
     *
     * @param $value
     */
    public function setFirstIpAttribute($value)
    {
        $this->attributes['first_ip'] = $value;
        $this->attributes['first_ip_int'] = ICF::InetPtoi($value);
    }

    /**
     * Updates the last IP attribute before giving it
     *
     * @param $value
     */
    public function setLastIpAttribute($value)
    {
        $this->attributes['last_ip'] = $value;
        $this->attributes['last_ip_int'] = ICF::InetPtoi($value);
    }
}
