<?php

namespace tests\Console\Commands\User;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    use DatabaseTransactions;

    private function initDB()
    {
        $this->user = factory(User::class)->create();
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'user:show',
            [
                'user' => 'xxx',
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('No matching user was found.', Artisan::output());
    }

    public function testWithValidFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'user:show',
            [
                'user' => $this->user->id,
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($this->user->first_name, Artisan::output());
    }
}
