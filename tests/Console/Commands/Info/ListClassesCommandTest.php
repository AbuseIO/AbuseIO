<?php

namespace Console\Commands\Info;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

class ListClassesCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testListClassesCommandShouldPassShowingHeadersWithoutArguments(): void
    {
        $headers = ['Tag', 'Name', 'Aliasses'];

        $exitCode = Artisan::call('info:list-classes');
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    #[group('functional')]
    public function testListClassesCommandShouldPassWithFilter(): void
    {
        $exitCode = Artisan::call('info:list-classes', [
            '--filter' => 'spam',
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('SPAM', Artisan::output());
    }

    #[group('functional')]
    public function testListClassesCommandShouldFailWithNonMatchingFilter(): void
    {
        $exitCode = Artisan::call('info:list-classes', [
            '--filter' => 'nonexistingclass',
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No classes found matching the filter criteria.', Artisan::output());
    }
}