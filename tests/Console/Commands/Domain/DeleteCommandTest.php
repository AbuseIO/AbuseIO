<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Domain;
use DateTime;
use DomainsTableSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    public function testValid()
    {
        $exitCode = Artisan::call(
            'domain:delete',
            [
                'id' => '1',
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('domain has been deleted', Artisan::output());

        Domain::withTrashed()->find(1)->restore();
    }

    public function testInvalidId()
    {
        $exitCode = Artisan::call(
            'domain:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals(Command::INVALID, $exitCode);
        $this->assertStringContainsString('Unable to find domain', Artisan::output());
    }
}
