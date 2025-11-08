<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    public function testValid()
    {
        // Create a netblock to ensure the ID exists and is owned by a valid contact
        $netblock = Netblock::factory()->create([
            'contact_id' => Contact::factory()->create()->id,
        ]);

        $exitCode = Artisan::call(
            'netblock:delete',
            [
                'id' => (string) $netblock->id,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('netblock has been deleted', Artisan::output());
        /*
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        $this->seed('NetblocksTableSeeder');
    }

    public function testInvalidId()
    {
        $exitCode = Artisan::call(
            'netblock:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals($exitCode, 1);
        $this->assertStringContainsString('Unable to find netblock', Artisan::output());
    }
}
