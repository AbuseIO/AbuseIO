<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Contact.
 *
 * @property int $id
 * @property string $reference fillable
 * @property string $name fillable
 * @property string $email fillable
 * @property string $api_host fillable
 * @property bool $auto_notify fillable
 * @property bool $enabled fillable
 * @property int account_id fillable
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 */
class Contact extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contacts';

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

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created.
     *
     * @return array $rules
     */
    public static function createRules()
    {
        $rules = [
            'reference'     => 'required|unique:contacts,reference',
            'name'          => 'required',
            'email'         => 'sometimes|emails',
            'api_host'      => 'sometimes|url',
            'enabled'       => 'required|boolean',
            'account_id'    => 'required|integer|exists:accounts,id',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated.
     *
     * @param \AbuseIO\Models\Contact $contact
     *
     * @return array $rules
     */
    public static function updateRules($contact)
    {
        $rules = [
            'reference'     => 'required|unique:contacts,reference,'.$contact->id,
            'name'          => 'required',
            'email'         => 'sometimes|emails',
            'api_host'      => 'sometimes|url',
            'enabled'       => 'required|boolean',
            'account_id'    => 'required|integer|exists:accounts,id',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the account for this contact.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Returns the domains for this contact.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * Returns the netblocks from this contact.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function netblocks()
    {
        return $this->hasMany(Netblock::class);
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

        $contact = self::find($model_id);

        return $contact->account->id == $account->id;
    }

    /**
     * Creates a shortlist of the table with ID and Name for pulldown menu's.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shortlist()
    {
        return $this->belongsTo('id', 'name');
    }
}
