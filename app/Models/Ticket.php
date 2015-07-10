<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ticket
 * @package AbuseIO\Models
 * @property string $ip
 * @property string $domain
 * @property integer $class_id
 * @property integer $type_id
 * @property string $email
 * @property string $ip_contact_reference
 * @property string $ip_contact_name
 * @property string $ip_contact_email
 * @property string $ip_contact_rpchost
 * @property string $ip_contact_rpckey
 * @property string $ip_contact_auto_notify
 * @property string $domain_contact_reference
 * @property string $domain_contact_name
 * @property string $domain_contact_email
 * @property string $domain_contact_rpchost
 * @property string $domain_contact_rpckey
 * @property string $domain_contact_auto_notify
 * @property integer $status_id
 * @property boolean auto_notify
 * @property integer $notified_count
 * @property integer $last_notify_count
 * @property integer $last_notify_timestamp
 */
class Ticket extends Model
{

    protected $table    = 'tickets';

    protected $fillable =
        [
            'ip',
            'domain',
            'class_id',
            'type_id',
            'ip_contact_reference',
            'ip_contact_name',
            'ip_contact_email',
            'ip_contact_rpchost',
            'ip_contact_rpckey',
            'ip_contact_auto_notify',
            'domain_contact_reference',
            'domain_contact_name',
            'domain_contact_email',
            'domain_contact_rpchost',
            'domain_contact_rpckey',
            'domain_contact_auto_notify',
            'status_id',
            'notified_count',
            'last_notify_count',
            'last_notify_timestamp'
        ];

    protected $guarded  = ['id'];

    public function events()
    {

        return $this->hasMany('AbuseIO\Models\Event')
            ->orderBy('timestamp', 'desc');

    }

    public function firstEvent()
    {

        return $this->hasMany('AbuseIO\Models\Event')
            ->orderBy('timestamp', 'asc')
            ->take(1);

    }

    public function lastEvent()
    {

        return $this->hasMany('AbuseIO\Models\Event')
            ->orderBy('timestamp', 'desc')
            ->take(1);

    }

    public function notes()
    {

        return $this->hasMany('AbuseIO\Models\Note');

    }
}
