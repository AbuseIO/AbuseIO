<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    public function testWithoutId()
    {
        ob_start();
        Artisan::call('domain:edit');
        $output = ob_get_clean();
        $this->assertStringContainsString('Edit a domain', $output);
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'domain:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals($exitCode, 1);
        $this->assertStringContainsString('Unable to find domain with this criteria', Artisan::output());
    }

    public function testWithInvalidContact()
    {
        // Create a valid domain to ensure the ID exists
        $domain = Domain::factory()->create([
            'contact_id' => Contact::factory()->create()->id,
        ]);

        $exitCode = Artisan::call(
            'domain:edit',
            [
                'id'           => (string) $domain->id,
                '--contact_id' => '100000',
            ]
        );
        $this->assertEquals($exitCode, 1);
        $this->assertStringContainsString('Unable to find contact with this criteria', Artisan::output());
    }

    public function testEnabled()
    {
        // Create a valid domain to ensure the ID exists
        $domain = Domain::factory()->create([
            'contact_id' => Contact::factory()->create()->id,
        ]);

        $exitCode = Artisan::call(
            'domain:edit',
            [
                'id'        => (string) $domain->id,
                '--enabled' => 'false',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('The domain has been updated', Artisan::output());
        /*
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        $this->seed('DomainsTableSeeder');
    }
}
