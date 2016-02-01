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
        'name' => $faker->name,
        'description' => $faker->sentence(rand(6, 10)),
        'disabled' =>  rand(0, 1),
        'systemaccount' => 0,
        'brand_id' => 1,
    ];
});

$factory->define(AbuseIO\Models\Brand::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'company_name' => $faker->company,
        'logo' => \AbuseIO\Models\Brand::getDefaultLogo(),
        'introduction_text' => $faker->realText(),
        'creator_id' => 1,
    ];
});

$factory->define(AbuseIO\Models\Contact::class, function (Faker\Generator $faker) {
    global $contact_reference_counter;
    if (! $contact_reference_counter) {
        $contact_reference_counter = 1;
    } else {
        $contact_reference_counter++;
    }

    return [
        'reference' => sprintf("reference_%d", $contact_reference_counter),
        'name' => $faker->name,
        'email' => $faker->email,
        'api_host' => 'api_host',
        'auto_notify' => $faker->boolean(),
        'enabled' => $faker->boolean(),
        'account_id' => AbuseIO\Models\Account::all()->first()->id,
    ];
});

$factory->define(AbuseIO\Models\Domain::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->domainName,
        'contact_id' => AbuseIO\Models\Contact::all()->first()->id,
        'enabled' => $faker->boolean(),
    ];
});
$factory->define(AbuseIO\Models\Event::class, function (Faker\Generator $faker) {
    return [
        'ticket_id'                 => \AbuseIO\Models\Ticket::all()->first()->id,
        'evidence_id'               => 1,
        'source'                    => $faker->name,
        'timestamp'                 => time(),
        'information'               => json_encode(
            [
                'engine' => $faker->sentence(5),
                'uri' => $faker->url
            ]
        )
    ];
});

/**
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




$factory->define(AbuseIO\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(AbuseIO\Models\Netblock::class, function (Faker\Generator $faker) {
    $first_ip = $faker->ipv4;
    $last_ip = long2ip(ip2long($first_ip) + rand(1, 100));

    return [
        'contact_id' => \AbuseIO\Models\Contact::all()->first()->id,
        'first_ip' => $first_ip,
        'last_ip' => $last_ip,
        'description' => $faker->sentence(rand(6, 24)),
        'enabled' => $faker->boolean(),
    ];
});





