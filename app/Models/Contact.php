<?php

namespace AbuseIO\Models;

use AbuseIO\Http\Requests\ContactFormRequest;
use Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Contact.
 *
 * @property int    $id
 * @property string $reference fillable
 * @property string $name      fillable
 * @property string $email     fillable
 * @property string $api_host  fillable
 * @property bool   $enabled   fillable
 * @property int account_id fillable
 * @property string $token
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $deleted_at
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
        'enabled',
        'account_id',
        'contact',
        'token',
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
            'reference'  => 'required|string|unique:contacts,reference',
            'name'       => 'required',
            'email'      => 'sometimes|emails',
            'api_host'   => 'sometimes|url',
            'enabled'    => 'required|boolean',
            'account_id' => 'required|integer|exists:accounts,id',
            //'notification_methods' => 'required|array',
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
            'reference'  => 'required|string|unique:contacts,reference,'.$contact->id,
            'name'       => 'required',
            'email'      => 'sometimes|emails',
            'api_host'   => 'sometimes|url',
            'enabled'    => 'required|boolean',
            'account_id' => 'required|integer|exists:accounts,id',
            //'notification_methods' => 'required|array',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being validate (required by findcontact!).
     *
     * @param \AbuseIO\Models\Contact $contact
     *
     * @return array $rules
     */
    public static function validateRules($contact)
    {
        $rules = [
            'reference'  => 'required',
            'name'       => 'required',
            'email'      => 'sometimes|emails',
            'api_host'   => 'sometimes|url',
            'enabled'    => 'required|boolean',
            'account_id' => 'required|integer|exists:accounts,id',
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

    /**
     * Returns the notification methods for this contact.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notificationMethods()
    {
        return $this->hasMany(ContactNotificationMethods::class);
    }

    /**
     * convenience method for determing if (old) functionality auto_notification is on or off;.
     *
     * @return bool
     */
    public function auto_notify()
    {
        return $this->hasNotificationMethod('Mail');
    }

    /**
     * Adds a notification method to this contact.
     *
     * @param $attributes
     *
     * @return $this
     */
    public function addNotificationMethod($attributes)
    {
        $this->notificationmethods()->create($attributes);

        return $this;
    }

    public function notificationMethodsAsString()
    {
        if ($this->notificationMethods->count() === 0) {
            return 'no notification methods set for this contact';
        }

        return $this->notificationMethods->implode('method', ', ');
    }

    /**
     * Sees whether a notification method is active for this contact.
     *
     * @param $method
     *
     * @return bool
     */
    public function hasNotificationMethod($method)
    {
        foreach ($this->notificationMethods as $notificationMethod) {
            if ($notificationMethod->method === $method) {
                return true;
            }
        }

        return false;
    }

    /**
     * Syncs the notificationMethods in the database.
     *
     * @param ContactFormRequest $contactForm
     */
    public function syncNotificationMethods(ContactFormRequest $contactForm)
    {
        $methods = $contactForm->get('notificationMethods');
        if ($methods == null) {
            $methods = [];
        }
        $methodsInDB = $this->notificationMethods->map(function ($item) {
            return $item->method;
        })->toArray();

        $toBeDeleted = array_diff($methodsInDB, $methods);
        $toBeInserted = array_diff($methods, $methodsInDB);

        foreach ($toBeInserted as $method) {
            $this->addNotificationMethod(['method' => $method]);
        }

        foreach ($toBeDeleted as $method) {
            foreach ($this->notificationMethods as $notificationMethod) {
                if ($notificationMethod->method === $method) {
                    $notificationMethod->delete();
                }
            }
        }
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

        return $contact->account->is($account);
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

    /**
     * Anonymizes the contact and returns the updated Contact.
     *
     * @param $randomness
     *
     * @return mixed
     */
    public function anonymize($randomness)
    {
        // retrieve settings
        $entropy = Config::get('app.key').$randomness;
        $anonymize_domain = Config::get('main.gdpr_anonymize_domain');

        // hash personal data and save it
        $this->reference = md5($entropy.$this->reference);
        $this->name = md5($entropy.$this->name);
        $this->email = md5($entropy.$this->email).'@'.$anonymize_domain;
        $this->api_host = '';
        $this->save();

        // get the updated Contact and return it
        $updated = self::find($this->id);

        return $updated;
    }
}
