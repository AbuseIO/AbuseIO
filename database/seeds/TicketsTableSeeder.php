<?php

use Illuminate\Database\Seeder;

class TicketsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('tickets')->delete();

        $tickets = [
            [
                'id'                            => 1,
                'ip'                            => '10.1.12.12',
                'domain'                        => 'domain13.com',
                'class_id'                      => 'COMPROMISED_WEBSITE',
                'type_id'                       => 'ABUSE',
                'ip_contact_account_id'         => 2,
                'ip_contact_reference'          => 'CONT2',
                'ip_contact_name'               => ' 2',
                'ip_contact_email'              => 'cont2@local.lan',
                'ip_contact_api_host'           => null,
                'ip_contact_auto_notify'        => 1,
                'ip_contact_notified_count'     => 0,
                'domain_contact_account_id'     => 2,
                'domain_contact_reference'      => 'CONT3',
                'domain_contact_name'           => ' 3',
                'domain_contact_email'          => 'cont3@local.lan',
                'domain_contact_api_host'       => null,
                'domain_contact_auto_notify'    => 1,
                'domain_contact_notified_count' => 0,
                'status_id'                     => 'OPEN',
                'contact_status_id'             => 'OPEN',
                'last_notify_count'             => 0,
                'last_notify_timestamp'         => null,
                'created_at'                    => new DateTime(),
                'updated_at'                    => new DateTime(),
            ],
            [
                'id'                            => 2,
                'ip'                            => '10.1.11.77',
                'domain'                        => null,
                'class_id'                      => 'BOTNET_INFECTION',
                'type_id'                       => 'ABUSE',
                'ip_contact_account_id'         => 1,
                'ip_contact_reference'          => 'CONT1',
                'ip_contact_name'               => ' 1',
                'ip_contact_email'              => 'cont1@local.lan',
                'ip_contact_api_host'           => null,
                'ip_contact_auto_notify'        => 1,
                'ip_contact_notified_count'     => 0,
                'domain_contact_account_id'     => 1,
                'domain_contact_reference'      => 'UNDEF',
                'domain_contact_name'           => 'Undefined Contact',
                'domain_contact_email'          => null,
                'domain_contact_api_host'       => null,
                'domain_contact_auto_notify'    => 0,
                'domain_contact_notified_count' => 0,
                'status_id'                     => 'ESCALATED',
                'contact_status_id'             => 'OPEN',
                'last_notify_count'             => 0,
                'last_notify_timestamp'         => null,
                'created_at'                    => new DateTime(),
                'updated_at'                    => new DateTime(),
            ],
            [
                'id'                            => 3,
                'ip'                            => '10.1.14.77',
                'domain'                        => null,
                'class_id'                      => 'OPEN_DNS_RESOLVER',
                'type_id'                       => 'INFO',
                'ip_contact_account_id'         => 1,
                'ip_contact_reference'          => 'CONT5',
                'ip_contact_name'               => ' 5',
                'ip_contact_email'              => 'cont1@local.lan',
                'ip_contact_api_host'           => null,
                'ip_contact_auto_notify'        => 1,
                'ip_contact_notified_count'     => 0,
                'domain_contact_account_id'     => 1,
                'domain_contact_reference'      => 'UNDEF',
                'domain_contact_name'           => 'Undefined Contact',
                'domain_contact_email'          => null,
                'domain_contact_api_host'       => null,
                'domain_contact_auto_notify'    => 0,
                'domain_contact_notified_count' => 0,
                'status_id'                     => 'OPEN',
                'contact_status_id'             => 'IGNORED',
                'last_notify_count'             => 0,
                'last_notify_timestamp'         => null,
                'created_at'                    => new DateTime(),
                'updated_at'                    => new DateTime(),
            ],
        ];

        DB::table('tickets')->insert($tickets);
    }
}
