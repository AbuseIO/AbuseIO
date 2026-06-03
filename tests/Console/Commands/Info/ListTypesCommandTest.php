<?php
namespace Console\Commands\Info;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

class ListTypesCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testListTypesCommandShouldPassShowingHeadersWithoutArguments(): void
    {
        $headers = ['Type', 'Description'];

        $exitCode = Artisan::call('info:list-types');
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    #[group('functional')]
    public function testListTypesCommandShouldPassWithFilterArgument(): void
    {
        $exitCode = Artisan::call('info:list-types', [
            '--filter' => 'INFO'
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('INFO', Artisan::output());
    }

    #[group('functional')]
    public function testListTypesCommandShouldFailWithNonMatchingFilterArgument(): void
    {
        $exitCode = Artisan::call('info:list-types', [
            '--filter' => 'NON_EXISTENT_TYPE'
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No types found matching the filter criteria.', Artisan::output());
    }
}