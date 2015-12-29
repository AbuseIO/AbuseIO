<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Contact
 * @package AbuseIO\Models
 * @property string $reference
 * @property string $name
 * @property string $email
 * @property string $api_host
 * @property string $api_key
 * @property boolean $auto_notify
 * @property boolean $enabled
 * @property integer account_id
 */
class Contact extends Model
{
    use SoftDeletes;

    protected $table    = 'contacts';

    protected $fillable = [
        'reference',
        'name',
        'email',
        'api_host',
        'api_key',
        'auto_notify',
        'enabled',
        'account_id',
    ];

    protected $guarded  = [
        'id'
    ];

    /**
     * Validation rules for this model being created
     *
     * @param  \AbuseIO\Models\Contact $contact
     * @return array $rules
     */
    public static function createRules(/** @noinspection PhpUnusedParameterInspection */ $contact)
    {
        $rules = [
            'reference' => 'required|unique:contacts,reference',
            'name'      => 'required',
            'email'     => 'sometimes|emails',
            'api_host'  => 'sometimes|url',
            'api_key'   => 'sometimes|string',
            'enabled'   => 'required|boolean',
            'account_id'=> 'required|integer'
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated
     *
     * @param  \AbuseIO\Models\Contact $contact
     * @return array $rules
     */
    public static function updateRules($contact)
    {
        $rules = [
            'reference' => 'required|unique:contacts,reference,'. $contact->id,
            'name'      => 'required',
            'email'     => 'sometimes|emails',
            'api_host'  => 'sometimes|url',
            'api_key'   => 'sometimes|string',
            'enabled'   => 'required|boolean',
            'account_id'=> 'required|integer'
        ];

        return $rules;
    }

    /**
     * Creates a shortlist of the table with ID and Name for pulldown menu's
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shortlist()
    {

        return $this->belongsTo('id', 'name');

    }

    /**
     * Returns the account for this contact
     *
     * @return Account
     */
    public function account()
    {
        return $this->belongsTo('AbuseIO\Models\Account');
    }

    /**
     * Returns the domains for this contact
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function domains()
    {

        return $this->hasMany('AbuseIO\Models\Domain');

    }

    /**
     * Returns the netblocks from this contact
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function netblocks()
    {

        return $this->hasMany('AbuseIO\Models\Netblock');

    }
}
