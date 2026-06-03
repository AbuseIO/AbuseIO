<?php

namespace tests\Console\Commands\Queue;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testQueueShowCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('queue:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "queue").', $output);
        $this->assertStringContainsString('Shows a queue', $output);
    }

    #[group('functional')]
    public function testQueueShowCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'queue:show',
            [
                'queue' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching queue was found.', Artisan::output());
    }
}
