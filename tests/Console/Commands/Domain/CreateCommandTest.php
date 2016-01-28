<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
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
        Artisan::call('domain:create');
        $output = Artisan::output();

        $this->assertContains('The name field is required.', $output);
        $this->assertContains('The contact id field is required.', $output);
        $this->assertContains('Failed to create the domain due to validation warnings', $output);
    }

    public function testValidCreate()
    {
        $faker = Factory::create();

        $domainName = $faker->domainName;

        Artisan::call('domain:create', [
            'name' => $domainName,
            'contact_id' => Contact::all()->first()->id
        ]);

        $this->assertContains(
            'The domain has been created',
            Artisan::output()
        );

        Domain::where('name', $domainName)->forceDelete();
    }
}
