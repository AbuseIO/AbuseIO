<?php

namespace tests\Console\Commands\Contact;

use AbuseIO\Models\Account;
use AbuseIO\Models\Contact;
use Faker\Factory;
use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    public function testWithoutArguments()
    {
        Artisan::call('contact:create');
        $output = Artisan::output();

        $this->assertContains('The reference field is required.', $output);
        $this->assertContains('The name field is required.', $output);
        $this->assertContains('The account id field is required.', $output);
        $this->assertContains('Failed to create the contact due to validation warnings', $output);
    }

    public function testValidCreate()
    {
        $faker = Factory::create();
        $name = $faker->name;


        Artisan::call('contact:create', [
            'name' => $name,
            'reference' => $faker->domainWord,
            'account_id' => Account::getSystemAccount()->id,
            'enabled' => $faker->boolean(),
            'email' => $faker->email,
            'api_host' => $faker->url,
        ]);

        $this->assertContains(
            'The contact has been created',
            Artisan::output()
        );

        Contact::where("name", $name)->forceDelete();
    }

    public function testCreateWithInvalidAccountId()
    {
        $faker = Factory::create();

        Artisan::call('contact:create', [
            'name' => $faker->name,
            'reference' => $faker->domainWord,
            'account_id' => '10000',
            'enabled' => $faker->boolean(),
            'email' => $faker->email,
            'api_host' => $faker->url,
        ]);

        $this->assertContains(
            'The selected account id is invalid.',
            Artisan::output()
        );
    }
}
