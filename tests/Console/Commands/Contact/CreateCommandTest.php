<?php

namespace tests\Console\Commands\Contact;

use AbuseIO\Models\Account;
use AbuseIO\Models\Contact;
use Faker\Factory;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    public function testWithoutArguments()
    {
        ob_start();
        Artisan::call('contact:create');
        $output = ob_get_clean();
        $this->assertStringContainsString('Creates a new contact', $output);
    }

    public function testValidCreate()
    {
        $faker = Factory::create();
        $name = $faker->name;

        Artisan::call('contact:create', [
            'name'       => $name,
            'reference'  => $faker->domainWord,
            'account_id' => Account::getSystemAccount()->id,
            'enabled'    => $faker->boolean(),
            'email'      => $faker->email,
            'api_host'   => $faker->url,
        ]);

        $this->assertStringContainsString(
            'The contact has been created',
            Artisan::output()
        );

        $contact = Contact::where('name', $name)->first();

        $this->assertEquals('', $contact->token);

        $contact->forceDelete();
    }

    public function testValidCreateWithApiToken()
    {
        $faker = Factory::create();
        $name = $faker->name;

        Artisan::call('contact:create', [
            'name'           => $name,
            'reference'      => $faker->domainWord,
            'account_id'     => Account::getSystemAccount()->id,
            'enabled'        => $faker->boolean(),
            'email'          => $faker->email,
            'api_host'       => $faker->url,
            '--with_api_key' => true,
        ]);

        $contact = Contact::where('name', $name)->first();

        $this->assertNotNull($contact->token);

        $this->assertStringContainsString(
            'The contact has been created',
            Artisan::output()
        );

        Contact::where('name', $name)->forceDelete();
    }

    public function testCreateWithInvalidAccountId()
    {
        $faker = Factory::create();

        Artisan::call('contact:create', [
            'name'       => $faker->name,
            'reference'  => $faker->domainWord,
            'account_id' => '10000',
            'enabled'    => $faker->boolean(),
            'email'      => $faker->email,
            'api_host'   => $faker->url,
        ]);

        $this->assertStringContainsString(
            'The selected account id is invalid.',
            Artisan::output()
        );
    }
}
