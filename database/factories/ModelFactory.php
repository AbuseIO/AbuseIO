<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(AbuseIO\Models\Account::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->name,
        'description'   => $faker->sentence(rand(6, 10)),
        'disabled'      => rand(0, 1),
        'token'         => generateApiToken(),
        'systemaccount' => 0,
        'brand_id'      => 1,
    ];
});

$factory->define(AbuseIO\Models\Brand::class, function (Faker\Generator $faker) {
    return [
        'name'              => $faker->name,
        'company_name'      => $faker->company,
        'logo'              => file_get_contents(\AbuseIO\Models\Brand::getDefaultLogo()->getPathname()),
        'introduction_text' => $faker->realText(),
        'creator_id'        => 1,
    ];
});

$factory->define(AbuseIO\Models\Contact::class, function (Faker\Generator $faker) {
    return [
        'reference'  => sprintf('reference_%s', uniqid()),
        'name'       => $faker->name,
        'email'      => $faker->safeEmail,
        'api_host'   => $faker->url,
        'enabled'    => $faker->boolean(),
        'account_id' => AbuseIO\Models\Account::all()->random()->id,
    ];
});

$factory->define(AbuseIO\Models\Domain::class, function (Faker\Generator $faker) {
    return [
        'name'       => uniqid().$faker->domainName,
        'contact_id' => AbuseIO\Models\Contact::all()->random()->id,
        'enabled'    => $faker->boolean(),
    ];
});
$factory->define(AbuseIO\Models\Event::class, function (Faker\Generator $faker) {
    $evidence = factory(\AbuseIO\Models\Evidence::class)->create();

    // get a random ticket
    $ticket = \AbuseIO\Models\Ticket::all()->random();

    // if no ticket found, we create our own
    $ticket = $ticket ?: factory(\AbuseIO\Models\Ticket::class)->create();

    return [
        'ticket_id'   => $ticket->id,
        'evidence_id' => $evidence->id,
        'source'      => $faker->name,
        'timestamp'   => time(),
        'information' => json_encode(
            [
                'engine' => $faker->sentence(5),
                'uri'    => $faker->url,
            ]
        ),
    ];
});

$factory->define(AbuseIO\Models\Evidence::class, function (Faker\Generator $faker) {
    /*
     TODO: this filename is one based on the original
     from the seeding command should be replace with something from
     /storage/mailarchive/$datefolder/$fileuuid.eml
    */

    $today = date('Ymd');

    return [
        'filename' => sprintf('mailarchive/%s/%s_messageid', $today, uniqid()),
        'sender'   => $faker->name,
        'subject'  => $faker->sentence(),
    ];
});

/*
 * TODO: figure out how to use Incident model factory and what values are relevant.
 */
//$factory->define(AbuseIO\Models\Incident::class, function (Faker\Generator $faker) {
//    return [
//        'source' => 'source',
//        'source_id' => 1,
//        'ip' => $faker->ipv4,
//        'domain' => $faker->domainName,
//        'uri' => $faker->url,
//        'timestamp' => time(),
//        'class' =>  array_rand((trans('classifications'))),
//        'type' => array_rand(config('types.type')),
//        'information' => $faker->sentence(60),
//    ];
//});

$factory->define(AbuseIO\Models\Netblock::class, function (Faker\Generator $faker) {

    // Randomize ipv4 or ipv6 generation
    if ($faker->boolean()) {
        $first_ip = $faker->ipv4;
        $last_ip = long2ip(ip2long($first_ip) + $faker->numberBetween(5, 100));
    } else {
        $first_ip = $faker->ipv6;
        $last_ip_int = inetPtoi($first_ip);
        $last_ip_int = bcadd($last_ip_int, $faker->numberBetween(1, 68719476736));
        $last_ip = inetItop($last_ip_int);
    }

    return [
        'contact_id'   => \AbuseIO\Models\Contact::all()->random()->id,
        'first_ip'     => $first_ip,
        'last_ip'      => $last_ip,
        'first_ip_int' => inetPtoi($first_ip),
        'last_ip_int'  => inetPtoi($last_ip),
        'description'  => $faker->sentence($faker->numberBetween(3, 5)),
        'enabled'      => $faker->boolean(),
    ];
});

$factory->define(AbuseIO\Models\Job::class, function (Faker\Generator $faker) {
    return [];
});

$factory->define(AbuseIO\Models\Note::class, function (Faker\Generator $faker) {

    // get a random ticket
    $ticket = \AbuseIO\Models\Ticket::all()->random();

    // if no ticket found, we create our own
    $ticket = $ticket ?: factory(\AbuseIO\Models\Ticket::class)->create();

    return [
        'ticket_id' => $ticket->id,
        'submitter' => $faker->userName,
        'text'      => $faker->sentence($faker->numberBetween(5, 10)),
        'hidden'    => $faker->boolean(),
        'viewed'    => $faker->boolean(),
    ];
});

//
//$factory->define(AbuseIO\Models\Origin::class, function (Faker\Generator $faker) {
//    return [];
//});
//
$factory->define(AbuseIO\Models\Permission::class, function (Faker\Generator $faker) {
    return [
        'name'        => $faker->name,
        'description' => $faker->sentence(6),
    ];
});
$factory->define(AbuseIO\Models\Role::class, function (Faker\Generator $faker) {
    return [
        'name'        => $faker->name,
        'description' => $faker->sentence(),
    ];
});

$factory->define(AbuseIO\Models\Ticket::class, function (Faker\Generator $faker) {
    $contactList = \AbuseIO\Models\Contact::all();

    /** @var \AbuseIO\Models\Contact $ipContact */
    $ipContact = $contactList->random();

    /** @var \AbuseIO\Models\Contact $domainContact */
    $domainContact = $contactList->random();

    $types = config('types.type');

    return [
        'ip'                            => $faker->boolean() ? $faker->ipv4 : $faker->ipv6,
        'domain'                        => $faker->domainName,
        'class_id'                      => array_rand(trans('classifications')),
        'type_id'                       => $types[array_rand($types)],
        'ip_contact_account_id'         => $ipContact->account_id,
        'ip_contact_reference'          => $ipContact->reference,
        'ip_contact_name'               => $ipContact->name,
        'ip_contact_email'              => $ipContact->email,
        'ip_contact_api_host'           => $ipContact->api_host,
        'ip_contact_auto_notify'        => $ipContact->auto_notify(),
        'ip_contact_notified_count'     => 0,
        'domain_contact_account_id'     => $domainContact->account_id,
        'domain_contact_reference'      => $domainContact->reference,
        'domain_contact_name'           => $domainContact->name,
        'domain_contact_email'          => $domainContact->email,
        'domain_contact_api_host'       => $domainContact->api_host,
        'domain_contact_auto_notify'    => $domainContact->auto_notify(),
        'domain_contact_notified_count' => 0,
        'status_id'                     => 'OPEN', //key(array_rand(config('status.abusedesk'))),
        'contact_status_id'             => 'OPEN', // key(array_rand(config('status.abusedesk'))),
        'last_notify_count'             => '1',
        'last_notify_timestamp'         => $faker->dateTime()->getTimestamp(),
    ];
});

$factory->define(AbuseIO\Models\User::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name'  => $faker->lastName,
        'email'      => $faker->safeEmail,
        'password'   => $faker->password(6),
        'account_id' => 1, //factory(\AbuseIO\Models\Account::class)->create(['disabled' => false]),
        'locale'     => 'en',
        'disabled'   => $faker->boolean(),
    ];
});

$factory->define(AbuseIO\Models\Job::class, function () {
    return [];
});

$factory->define(AbuseIO\Models\TicketGraphPoint::class, function () {
    return [];
});
