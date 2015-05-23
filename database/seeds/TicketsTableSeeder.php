<?php

use Illuminate\Database\Seeder;

class TicketsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('tickets')->delete();

        $tickets = array(
            [
                'id'                        => 1,
                'ip'                        => '10.1.12.12',
                'domain'                    => 'domain13.com',
                'first_seen'                => new DateTime,
                'last_seen'                 => new DateTime,
                'class_id'                  => 1,
                'type_id'                   => 1,
                'ip_contact_reference'      => 'CUST2',
                'ip_contact_name'           => 'Customer 2',
                'ip_contact_email'          => 'cust2@local.lan',
                'ip_contact_rpchost'        => null,
                'ip_contact_rpckey'         => null,
                'domain_contact_reference'  => 'CUST3',
                'domain_contact_name'       => 'Customer 3',
                'domain_contact_email'      => 'cust3@local.lan',
                'domain_contact_rpchost'    => null,
                'domain_contact_rpckey'     => null,
                'status_id'                 => 1,
                'auto_notify'               => 1,
                'notified_count'            => 0,
                'report_count'              => 1,
                'last_notify_count'         => 0,
                'last_notify_timestamp'     => null,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => 2,
                'ip'                        => '10.1.11.77',
                'domain'                    => null,
                'first_seen'                => new DateTime,
                'last_seen'                 => new DateTime,
                'class_id'                  => 2,
                'type_id'                   => 2,
                'ip_contact_reference'      => 'CUST1',
                'ip_contact_name'           => 'Customer 1',
                'ip_contact_email'          => 'cust1@local.lan',
                'ip_contact_rpchost'        => null,
                'ip_contact_rpckey'         => null,
                'domain_contact_reference'  => null,
                'domain_contact_name'       => null,
                'domain_contact_email'      => null,
                'domain_contact_rpchost'    => null,
                'domain_contact_rpckey'     => null,
                'status_id'                 => 1,
                'auto_notify'               => 1,
                'notified_count'            => 0,
                'report_count'              => 1,
                'last_notify_count'         => 0,
                'last_notify_timestamp'     => null,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ]
        );

        DB::table('tickets')->insert($tickets);
    }

}
