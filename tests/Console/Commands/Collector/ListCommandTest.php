<?php

namespace tests\Console\Commands\Collector;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testHeaders()
    {
        $exitCode = Artisan::call('collector:list', []);

        $this->assertEquals(Command::SUCCESS, $exitCode);

        $headers = ['Name', 'Description'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    public function testAll()
    {
        $exitCode = Artisan::call('collector:list', []);

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $output = Artisan::output();
        $this->assertStringContainsString('Rbl', $output);
        $this->assertStringContainsString('Snds', $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'collector:list',
            [
                '--filter' => 'Rbl',
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $output = Artisan::output();
        $this->assertStringContainsString('Rbl', $output);
        $this->assertStringNotContainsString('Snds', $output);
    }

    //    public function testNotFoundFilter()
//    {
//        $exitCode = Artisan::call(
//            'collector:list',
//            [
//                '--filter' => 'xxx',
//            ]
//        );
//
//        $this->assertEquals(Command::SUCCESS, $exitCode);
//        //$this->assertStringContainsString('No matching collector was found', Artisan::output());
//    }
}
