<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Netblock.
 *
 * @property int    $id
 * @property string $first_ip    fillable
 * @property string $last_ip     fillable
 * @property string $description fillable
 * @property int    $contact_id  fillable
 * @property bool   $enabled     fillable
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $deleted_at
 */
class Netblock extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'netblocks';

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
        'enabled',
    ];

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created.
     *
     * @param \AbuseIO\Models\Netblock $netblock
     *
     * @return array $rules
     */
    public static function createRules($netblock)
    {
        $rules = [
            'first_ip'    => "required|ip|unique:netblocks,first_ip,NULL,id,last_ip,{$netblock->last_ip}",
            'last_ip'     => "required|ip|unique:netblocks,last_ip,NULL,id,first_ip,{$netblock->first_ip}",
            'contact_id'  => 'required|integer|exists:contacts,id',
            'description' => 'required',
            'enabled'     => 'required|boolean',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated.
     *
     * @param \AbuseIO\Models\Netblock $netblock
     *
     * @return array $rules
     */
    public static function updateRules($netblock)
    {
        $rules = [
            'first_ip'    => "required|ip|unique:netblocks,first_ip,{$netblock->id},id,last_ip,{$netblock->last_ip}",
            'last_ip'     => "required|ip|unique:netblocks,last_ip,{$netblock->id},id,first_ip,{$netblock->first_ip}",
            'contact_id'  => 'required|integer|exists:contacts,id',
            'description' => 'required',
            'enabled'     => 'required|boolean',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the contact for this netblock.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Updates the first IP attribute before giving it.
     *
     * @param string $value
     */
    public function setFirstIpAttribute($value)
    {
        $this->attributes['first_ip'] = $value;
        $this->attributes['first_ip_int'] = inetPtoi($value);
    }

    /**
     * Updates the last IP attribute before giving it.
     *
     * @param string $value
     */
    public function setLastIpAttribute($value)
    {
        $this->attributes['last_ip'] = $value;
        $this->attributes['last_ip_int'] = inetPtoi($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Static method to check if the account has access to the model instance.
     *
     * @param int                     $model_id
     * @param \AbuseIO\Models\Account $account
     *
     * @return bool
     */
    public static function checkAccountAccess($model_id, Account $account)
    {
        // Early return when we are in the system account
        if ($account->isSystemAccount()) {
            return true;
        }

        $netblock = self::find($model_id);

        return $netblock->contact->account->is($account);
    }
}
