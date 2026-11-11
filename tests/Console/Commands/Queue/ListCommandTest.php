<?php

namespace Console\Commands\Queue;

use Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class ListCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testListQueueCommandShouldPassToShowTableHeaders(): void
    {
        $headers = ['Queue', 'Jobs'];

        $exitCode = Artisan::call('queue:list');
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    #[group('functional')]
    public function testListQueueCommandShouldPassWithValidFilter(): void
    {
        $exitCode = Artisan::call('queue:list', [
            '--filter' => 'abuseio_collector'
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('abuseio_collector', Artisan::output());
    }

    #[group('functional')]
    public function testListQueueCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call('queue:list', [
            '--filter' => 'xxx'
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching queue was found.', Artisan::output());
    }
}