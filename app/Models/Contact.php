<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Contact
 * @package AbuseIO\Models
 * @property string $reference
 * @property string $name
 * @property string $email
 * @property string $rpc_host
 * @property string $rpc_key
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
        'rpc_host',
        'rpc_key',
        'auto_notify',
        'enabled',
        'account_id',
    ];

    protected $guarded  = [
        'id'
    ];

    /*
     * Validation rules for this model being created
     *
     * @param  Model $contact
     * @return Array $rules
     */
    public function createRules($contact)
    {
        $rules = [
            'reference' => 'required|unique:contacts,reference',
            'name'      => 'required',
            'email'     => 'required|emails',
            'rpc_host'  => 'sometimes|url',
            'enabled'   => 'required|boolean',
        ];

        return $rules;
    }

    /*
     * Validation rules for this model being updated
     *
     * @param  Model $contact
     * @return Array $rules
     */
    public function updateRules($contact)
    {
        $rules = [
            'reference' => 'required|unique:contacts,reference,'. $contact->id,
            'name'      => 'required',
            'email'     => 'required|emails',
            'rpc_host'  => 'sometimes|url',
            'enabled'   => 'required|boolean',
        ];

        return $rules;
    }

    public function shortlist()
    {

        return $this->belongsTo('id', 'name');

    }

    /**
     * @return Account
     */
    public function account()
    {
        return $this->belongsTo('AbuseIO\Models\Account');
    }

    public function domains()
    {

        return $this->hasMany('AbuseIO\Models\Domain');

    }

    public function netblocks()
    {

        return $this->hasMany('AbuseIO\Models\Netblock');

    }
}
