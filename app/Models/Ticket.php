<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

    protected $table    = 'tickets';
    protected $fillable = [
        'ip',
        'domain',
        'first_seen',
        'last_seen',
        'class_id',
        'type_id',
        'ip_contact_reference',
        'ip_contact_name',
        'ip_contact_email',
        'ip_contact_rpchost',
        'ip_contact_rpckey',
        'domain_contact_reference',
        'domain_contact_name',
        'domain_contact_email',
        'domain_contact_rpchost',
        'domain_contact_rpckey',
        'status_id',
        'auto_notify',
        'notified_count',
        'report_count',
        'last_notify_count',
        'last_notify_timestamp'
    ];
    protected $guarded  = ['id'];

}
