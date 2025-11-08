<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
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
        Artisan::call('netblock:edit');
        $this->assertStringContainsString('Edit a netblock', ob_get_clean());
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'netblock:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Unable to find netblock with this criteria', Artisan::output());
    }

    public function testWithInvalidContact()
    {
        // Create a valid netblock to ensure the ID exists
        $netblock = Netblock::factory()->create([
            'contact_id' => Contact::factory()->create()->id,
        ]);

        $exitCode = Artisan::call(
            'netblock:edit',
            [
                'id'           => (string) $netblock->id,
                '--contact_id' => '100000', // invalid contact id
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Unable to find contact with this criteria', Artisan::output());
    }

    public function testEnabled()
    {
        // Create a valid netblock to ensure the ID exists
        $netblock = Netblock::factory()->create([
            'contact_id' => Contact::factory()->create()->id,
        ]);

        $exitCode = Artisan::call(
            'netblock:edit',
            [
                'id'        => (string) $netblock->id,
                '--enabled' => 'false',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('The netblock has been updated', Artisan::output());
        /*
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        $this->seed('NetblocksTableSeeder');
    }
}
