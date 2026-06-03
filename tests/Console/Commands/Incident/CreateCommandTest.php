<?php

namespace Console\Commands\Incident;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

class CreateCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('unit')]
    public function testIncidentCommandShouldFailWithNoArguments(): void
    {
        $exitCode = Artisan::call('incident:create');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "source, ip, domain, class, type, timestamp, information").', $output);
        $this->assertStringContainsString('Creates a new incident', $output);
    }

    #[group('functional')]
    public function testIncidentCommandShouldFailWithEmptyEvidenceFile(): void
    {
        $exitCode = Artisan::call(
            'incident:create',
            [
                'source' => 'unittest',
                'ip' => '127.1.1.1',
                'domain' => 'example.com',
                'class' => 'abuse',
                'type' => 'INFO',
                'timestamp' => now(),
                'information' => 'Unit test incident creation',
                'file' => '',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString("File does not exist: ", Artisan::output());
    }

    #[group('functional')]
    public function testIncidentCommandShouldFailWithNonExistingEvidenceFile(): void
    {
        $exitCode = Artisan::call(
            'incident:create',
            [
                'source' => 'unittest',
                'ip' => '127.1.1.1',
                'domain' => 'example.com',
                'class' => 'abuse',
                'type' => 'INFO',
                'timestamp' => now(),
                'information' => 'Unit test incident creation',
                'file' => 'test.pdf',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString("File does not exist: ", Artisan::output());
    }

    #[group('functional')]
    public function testIncidentCommandWithValidEvidenceFile(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'evidence_');
        file_put_contents($tempFile, 'This is a test evidence file.');

        $exitCode = Artisan::call(
            'incident:create',
            [
                'source' => 'unittest',
                'ip' => '127.1.1.1',
                'domain' => 'example.com',
                'class' => 'abuse',
                'type' => 'INFO',
                'timestamp' => time(),
                'information' => 'Unit test incident creation',
                'file' => $tempFile,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString("The incident has been created", Artisan::output());
    }

}
