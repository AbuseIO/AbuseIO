<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Contact
 * @package AbuseIO\Models
 * @property integer $id guarded
 * @property string $reference
 * @property string $name
 * @property string $email
 * @property string $api_host
 * @property boolean $auto_notify
 * @property boolean $enabled
 * @property integer account_id
 * @property integer $created_at guarded
 * @property integer $updated_at guarded
 * @property integer $deleted_at guarded
 */
class Contact extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table    = 'contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference',
        'name',
        'email',
        'api_host',
        'auto_notify',
        'enabled',
        'account_id',
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
     * @return array $rules
     */
    public static function createRules()
    {
        $rules = [
            'reference' => 'required|unique:contacts,reference',
            'name'      => 'required',
            'email'     => 'sometimes|emails',
            'api_host'  => 'sometimes|url',
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
            'enabled'   => 'required|boolean',
            'account_id'=> 'required|integer'
        ];

        return $rules;
    }

    /**
     * Static method to check if the account has access to the model instance
     *
     * @param $model_id
     * @param $account
     * @return bool
     */
    public static function checkAccountAccess($model_id, $account)
    {
        // early return when we are in the system account
        if ($account->isSystemAccount())
            return true;

        $contact = Contact::find($model_id);
        return ($contact->account->id == $account->id);
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
