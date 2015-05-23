<?php namespace AbuseIO\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

    protected $class_id = [
        100 => 'Botnet controller',
        101 => 'Botnet infection',
        102 => 'Compromised server',
        103 => 'Compromised website',
        104 => 'Distribution website',
        105 => 'FREAK Vulnerable Server',
        106 => 'Harvesting',
        107 => 'Notice and Take Down request',
        108 => 'Open Chargen Server',
        109 => 'Open DNS Resolver',
        110 => 'Open IPMI Server',
        111 => 'Open MemCached Server',
        112 => 'Open Microsoft SQL Server',
        113 => 'Open MongoDB Server',
        114 => 'Open NAT_PMP Server',
        115 => 'Open NTP Server',
        116 => 'Open Netbios Server',
        117 => 'Open QOTD Server',
        118 => 'Open REDIS Server',
        119 => 'Open SNMP Server',
        120 => 'Open SSDP Server',
        121 => 'Phishing website',
        122 => 'Possible DDOS sending DNS Server',
        123 => 'Possible DDOS sending NTP Server',
        124 => 'RBL Listed',
        125 => 'SPAM',
        126 => 'SPAM Trap',
        127 => 'SSLv3 Vulnerable Server',
        128 => 'Spamvertised web site',
    ];

    protected $type_id = [
        1 => 'Info',
        2 => 'Abuse',
        3 => 'Escalation',
    ];

    protected $status_id = [
        11 => 'Open',
        12 => 'Closed',
    ];

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
