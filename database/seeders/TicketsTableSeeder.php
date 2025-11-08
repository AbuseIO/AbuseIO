<?php

namespace Database\Seeders;

use AbuseIO\Models\Account;
use AbuseIO\Models\Ticket;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class TicketsTableSeeder extends Seeder
{
    public function run()
    {
        $defaultAccount = Account::query()->orderBy('id')->first();
        $customerAccount = Account::query()->where('name', 'Customer Internet')->first() ?? $defaultAccount;
        $businessAccount = Account::query()->where('name', 'Business Internet')->first() ?? $defaultAccount;

        $tickets = [
            [
                'ip'                            => '10.1.12.12',
                'domain'                        => 'domain13.com',
                'class_id'                      => 'COMPROMISED_WEBSITE',
                'type_id'                       => 'ABUSE',
                'ip_contact_account_id'         => optional($customerAccount)->id,
                'ip_contact_reference'          => 'CONT2',
                'ip_contact_name'               => ' 2',
                'ip_contact_email'              => 'cont2@local.lan',
                'ip_contact_api_host'           => '',
                'ip_contact_auto_notify'        => 1,
                'ip_contact_notified_count'     => 0,
                'domain_contact_account_id'     => optional($customerAccount)->id,
                'domain_contact_reference'      => 'CONT3',
                'domain_contact_name'           => ' 3',
                'domain_contact_email'          => 'cont3@local.lan',
                'domain_contact_api_host'       => '',
                'domain_contact_auto_notify'    => 1,
                'domain_contact_notified_count' => 0,
                'status_id'                     => 'OPEN',
                'contact_status_id'             => 'OPEN',
                'last_notify_count'             => 0,
                'last_notify_timestamp'         => time(),
                'created_at'                    => new DateTime(),
                'updated_at'                    => new DateTime(),
            ],
            [
                'ip'                            => '10.1.11.77',
                'domain'                        => null,
                'class_id'                      => 'BOTNET_INFECTION',
                'type_id'                       => 'ABUSE',
                'ip_contact_account_id'         => optional($defaultAccount)->id,
                'ip_contact_reference'          => 'CONT1',
                'ip_contact_name'               => ' 1',
                'ip_contact_email'              => 'cont1@local.lan',
                'ip_contact_api_host'           => '',
                'ip_contact_auto_notify'        => 1,
                'ip_contact_notified_count'     => 0,
                'domain_contact_account_id'     => optional($defaultAccount)->id,
                'domain_contact_reference'      => 'UNDEF',
                'domain_contact_name'           => 'Undefined Contact',
                'domain_contact_email'          => '',
                'domain_contact_api_host'       => '',
                'domain_contact_auto_notify'    => 0,
                'domain_contact_notified_count' => 0,
                'status_id'                     => 'ESCALATED',
                'contact_status_id'             => 'OPEN',
                'last_notify_count'             => 0,
                'last_notify_timestamp'         => time(),
                'created_at'                    => new DateTime(),
                'updated_at'                    => new DateTime(),
            ],
            [
                'ip'                            => '10.1.14.77',
                'domain'                        => null,
                'class_id'                      => 'OPEN_DNS_RESOLVER',
                'type_id'                       => 'INFO',
                'ip_contact_account_id'         => optional($defaultAccount)->id,
                'ip_contact_reference'          => 'CONT5',
                'ip_contact_name'               => ' 5',
                'ip_contact_email'              => 'cont1@local.lan',
                'ip_contact_api_host'           => '',
                'ip_contact_auto_notify'        => 1,
                'ip_contact_notified_count'     => 0,
                'domain_contact_account_id'     => optional($defaultAccount)->id,
                'domain_contact_reference'      => 'UNDEF',
                'domain_contact_name'           => 'Undefined Contact',
                'domain_contact_email'          => '',
                'domain_contact_api_host'       => '',
                'domain_contact_auto_notify'    => 0,
                'domain_contact_notified_count' => 0,
                'status_id'                     => 'OPEN',
                'contact_status_id'             => 'IGNORED',
                'last_notify_count'             => 0,
                'last_notify_timestamp'         => time(),
                'created_at'                    => new DateTime(),
                'updated_at'                    => new DateTime(),
            ],
        ];

        // Generate ash tokens so ASH public endpoints can be accessed in tests
        $salt = Config::get('app.key');
        foreach ($tickets as &$t) {
            $ipRef = $t['ip_contact_reference'] ?? '';
            $domainRef = $t['domain_contact_reference'] ?? '';
            $domain = $t['domain'] ?? '';

            $t['ash_token_ip'] = md5($salt.rand().$t['ip'].$ipRef);
            $t['ash_token_domain'] = md5($salt.rand().$domain.$domainRef);
        }
        unset($t);

        foreach ($tickets as $row) {
            Ticket::query()->updateOrCreate(
                [
                    'ip'       => $row['ip'],
                    'domain'   => $row['domain'],
                    'class_id' => $row['class_id'],
                    'type_id'  => $row['type_id'],
                ],
                $row
            );
        }
    }
}
