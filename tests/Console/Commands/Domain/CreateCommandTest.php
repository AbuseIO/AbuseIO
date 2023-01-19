<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use Faker\Factory;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command;
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
        $this->assertEquals(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Creates a new domain', ob_get_clean());
    }

    public function testValidCreate()
    {
        $faker = Factory::create();

        $domainName = $faker->domainName;

        $exitCode = Artisan::call('domain:create', [
            'name'       => $domainName,
            'contact_id' => Contact::all()->first()->id,
        ]);

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString(
            'The domain has been created',
            Artisan::output()
        );

        Domain::where('name', $domainName)->forceDelete();
    }
}
