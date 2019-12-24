<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
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
        $exitCode = Artisan::call('domain:create');
        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Creates a new domain', ob_get_clean());
    }

    public function testValidCreate()
    {
        $faker = Factory::create();

        $domainName = $faker->domainName;

        Artisan::call('domain:create', [
            'name'       => $domainName,
            'contact_id' => Contact::all()->first()->id,
        ]);

        $this->assertStringContainsString(
            'The domain has been created',
            Artisan::output()
        );

        Domain::where('name', $domainName)->forceDelete();
    }
}
