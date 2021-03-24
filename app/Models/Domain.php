<?php

namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Domain.
 *
 * @property int    $id
 * @property string $name       fillable
 * @property int    $contact_id fillable
 * @property bool   $enabled    fillable
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $deleted_at
 */
class Domain extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'domains';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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
     * @return array $rules
     */
    public static function createRules()
    {
        $rules = [
            'name'       => 'required|stringorboolean|domain|unique:domains',
            'contact_id' => 'required|integer|exists:contacts,id',
            'enabled'    => 'required|boolean',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated.
     *
     * @param \AbuseIO\Models\Domain $domain
     *
     * @return array $rules
     */
    public static function updateRules($domain)
    {
        $rules = [
            'name'       => 'required|stringorboolean|domain|unique:domains,name,'.$domain->id,
            'contact_id' => 'required|integer|exists:contacts,id',
            'enabled'    => 'required|boolean',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the contact for this domain.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
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

        $domain = self::find($model_id);

        return $domain->contact->account->is($account);
    }
}
