<?php

namespace tests\Console\Commands\Evidence;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    public function testInvalidId()
    {
        $exitCode = Artisan::call(
            'evidence:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Unable to find evidence', Artisan::output());
    }

    public function testValidIdButWithEvents()
    {
        $exitCode = Artisan::call(
            'evidence:delete',
            [
                'id' => '1',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Couldn\'t delete evidence because it is used in events' , Artisan::output());
    }
}
