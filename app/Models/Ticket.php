<?php

namespace AbuseIO\Models;

use AbuseIO\Jobs\FindContact;
use Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ticket.
 *
 * @property int    $id
 * @property string $ip                            fillable
 * @property string $domain                        fillable
 * @property string $class_id                      fillable
 * @property string $type_id                       fillable
 * @property string $ip_contact_account_id         fillable
 * @property string $ip_contact_reference          fillable
 * @property string $ip_contact_name               fillable
 * @property string $ip_contact_email              fillable
 * @property string $ip_contact_api_host           fillable
 * @property string $ip_contact_auto_notify        fillable
 * @property int    $ip_contact_notified_count     fillable
 * @property string $domain_contact_account_id     fillable
 * @property string $domain_contact_reference      fillable
 * @property string $domain_contact_name           fillable
 * @property string $domain_contact_email          fillable
 * @property string $domain_contact_api_host       fillable
 * @property string $domain_contact_auto_notify    fillable
 * @property int    $domain_contact_notified_count fillable
 * @property int    $status_id                     fillable
 * @property string $contact_status_id             fillable
 * @property int    $account_id                    fillable
 * @property bool   $auto_notify                   fillable
 * @property int    $last_notify_count             fillable
 * @property int    $last_notify_timestamp         fillable
 * @property string $remote_api_token
 * @property string $api_token
 * @property string $remote_api_url
 * @property int    $remote_ticket_id
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $deleted_at
 */
class Ticket extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tickets';

    /**
     * a writeonce property containing the api_token;.
     *
     * @var string
     */
    protected $api_token;

    /**
     * a property containing the remote api token;.
     *
     * @var string
     */
    protected $remote_api_token;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'domain',
        'class_id',
        'type_id',
        'ip_contact_account_id',
        'ip_contact_reference',
        'ip_contact_name',
        'ip_contact_email',
        'ip_contact_api_host',
        'ip_contact_auto_notify',
        'ip_contact_notified_count',
        'domain_contact_account_id',
        'domain_contact_reference',
        'domain_contact_name',
        'domain_contact_email',
        'domain_contact_api_host',
        'domain_contact_auto_notify',
        'domain_contact_notified_count',
        'status_id',
        'contact_status_id',
        'last_notify_count',
        'last_notify_timestamp',
        'ash_token_ip',
        'ash_token_domain',
        'remote_api_url',
        'remote_api_token',
        'remote_ticket_id',
        'api_token',
        'remote_ash_link',
    ];

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for this model being created.
     *
     * @return array
     */
    public static function createRules()
    {
        $rules = [
            'ip'                            => 'required|ip',
            'domain'                        => 'sometimes|stringorboolean|domain',
            'class_id'                      => 'required|string|max:100',
            'type_id'                       => 'required|in:INFO,ABUSE,ESCALATION',
            'ip_contact_account_id'         => 'required|integer|exists:accounts,id',
            'ip_contact_reference'          => 'required|string',
            'ip_contact_name'               => 'required|string',
            'ip_contact_email'              => 'sometimes|emails',
            'ip_contact_api_host'           => 'sometimes|string',
            'ip_contact_auto_notify'        => 'required|boolean',
            'ip_contact_notified_count'     => 'required|integer',
            'domain_contact_account_id'     => 'required|integer|exists:accounts,id',
            'domain_contact_reference'      => 'required|string',
            'domain_contact_name'           => 'required|string',
            'domain_contact_email'          => 'sometimes|emails',
            'domain_contact_api_host'       => 'sometimes|string',
            'domain_contact_auto_notify'    => 'required|boolean',
            'domain_contact_notified_count' => 'required|integer',
            'status_id'                     => 'required|in:OPEN,CLOSED,ESCALATED,RESOLVED,IGNORED',
            'contact_status_id'             => 'sometimes|in:OPEN,RESOLVED,IGNORED',
            'last_notify_count'             => 'required|integer',
            'last_notify_timestamp'         => 'required|timestamp',
        ];

        return $rules;
    }

    /**
     * Validation rules for this model being updated.
     *
     * @return array
     */
    public static function updateRules()
    {
        $rules = [
            'ip'                            => 'required|ip',
            'domain'                        => 'sometimes|string',
            'class_id'                      => 'required|string|max:100',
            'type_id'                       => 'required|in:INFO,ABUSE,ESCALATION',
            'ip_contact_account_id'         => 'required|integer|exists:contacts,id',
            'ip_contact_reference'          => 'required|string',
            'ip_contact_name'               => 'required|string',
            'ip_contact_email'              => 'sometimes|emails',
            'ip_contact_api_host'           => 'sometimes|string',
            'ip_contact_auto_notify'        => 'required|boolean',
            'ip_contact_notified_count'     => 'required|integer',
            'domain_contact_account_id'     => 'required|integer|exists:contacts,id',
            'domain_contact_reference'      => 'required|string',
            'domain_contact_name'           => 'required|string',
            'domain_contact_email'          => 'sometimes|emails',
            'domain_contact_api_host'       => 'sometimes|string',
            'domain_contact_auto_notify'    => 'required|boolean',
            'domain_contact_notified_count' => 'required|integer',
            'status_id'                     => 'required|in:OPEN,CLOSED,ESCALATED,RESOLVED,IGNORED',
            'contact_status_id'             => 'sometimes|in:OPEN,RESOLVED,IGNORED',
            'last_notify_count'             => 'required|integer',
            'last_notify_timestamp'         => 'required|timestamp',
        ];

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Methods
    |--------------------------------------------------------------------------
    */

    /**
     * One-to-Many relationship to Event.
     *
     * @param string $order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events($order = 'asc')
    {
        return $this->hasMany(Event::class)
            ->orderBy('timestamp', $order);
    }

    /**
     * One-to-Many relationship to Note.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * one-to-many relationship to Note.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unreadNotes()
    {
        return $this->hasMany(Note::class)
            ->where('viewed', 'false');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function firstEvent()
    {
        return $this->events('asc')->take(1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function lastEvent()
    {
        return $this->events('desc')->take(1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountIp()
    {
        return $this->belongsTo(Account::class, 'ip_contact_account_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountDomain()
    {
        return $this->belongsTo(Account::class, 'domain_contact_account_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Return the Last Notified attribute.
     *
     * @return bool|string
     */
    public function getLastNotifiedAttribute()
    {
        return date(
            config('app.date_format'),
            $this->attributes['last_notify_timestamp']
        );
    }

    /**
     * Return the Updated attribute.
     *
     * @param string $date
     *
     * @return bool|string
     */
    public function getUpdatedAtAttribute($date)
    {
        return date(
            config('app.date_format'),
            strtotime($date)
        );
    }

    /**
     * Return the Created Ar attribute.
     *
     * @param $date
     *
     * @return bool|string
     */
    public function getCreatedAtAttribute($date)
    {
        return date(
            config('app.date_format'),
            strtotime($date)
        );
    }

    /**
     * return the numbers of events.
     *
     * @return int
     */
    public function getEventCountAttribute()
    {
        return count($this->events);
    }

    /**
     * return the numbers of notes.
     *
     * @return int
     */
    public function getNoteCountAttribute()
    {
        return count($this->notes);
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Static method to check if the account has access to the model instance.
     *
     * @param  $model_id                        Model Id
     * @param \AbuseIO\Models\Account $account The Account Model
     *
     * @return bool
     */
    public static function checkAccountAccess($model_id, Account $account)
    {
        // Early return when we are in the system account
        if ($account->isSystemAccount()) {
            return true;
        }

        $ticket = self::find($model_id);

        return ($ticket->accountIp->is($account)) ||
            ($ticket->accountDomain->is($account));
    }

    /**
     * has this ticket a parent ticket in another AbuseIO instance ?
     *
     * @return bool
     */
    public function hasParent()
    {
        // just checking for the remote_ticket_id should be enough
        $result = !empty($this->remote_ticket_id);

        return $result;
    }

    /**
     * has this ticket a child ticket in another AbuseIO instance ?
     *
     * @return bool
     */
    public function hasChild()
    {
        // we assume that, if the ip contact has an api url then the ticket has a child ticket
        $contact = FindContact::byIP($this->ip);

        return !empty($contact->api_host);
    }

    /**
     * permanently deletes a ticket.
     *
     * @return bool
     */
    public function purge()
    {
        if ($this->trashed() or $this->deleted_at != null) {
            $this->forceDelete();

            return true;
        }

        return false;
    }

    /**
     * Anonymize the personal data in the ticket.
     *
     * @param $email
     * @param $randomness
     *
     * @return mixed
     */
    public function anonymize($email, $randomness)
    {
        // retrieve settings
        $entropy = Config::get('app.key').$randomness;
        $anonymize_domain = Config::get('main.gdpr_anonymize_domain');

        // hash personal data and save it
        // ip contact data
        if ($this->ip_contact_email == $email) {
            $this->ip_contact_email = md5($entropy.$this->ip_contact_email).'@'.$anonymize_domain;
            $this->ip_contact_reference = md5($entropy.$this->ip_contact_reference);
            $this->ip_contact_name = md5($entropy.$this->ip_contact_name);
            $this->ip_contact_api_host = '';
        }

        // domain contact data
        if ($this->domain_contact_email == $email) {
            $this->domain_contact_email = md5($entropy.$this->domain_contact_email).'@'.$anonymize_domain;
            $this->domain_contact_reference = md5($entropy.$this->domain_contact_reference);
            $this->domain_contact_name = md5($entropy.$this->domain_contact_name);
            $this->domain_contact_api_host = '';
        }

        $this->save();

        // get the updated Contact and return it
        $updated = self::find($this->id);

        return $updated;
    }
}
