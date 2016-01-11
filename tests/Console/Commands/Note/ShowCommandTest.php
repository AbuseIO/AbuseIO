<?php

namespace tests\Console\Commands\Note;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

/**
 * Class ShowCommandTest
 * @package tests\Console\Commands\Note
 */
class ShowCommandTest extends TestCase
{

    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call(
            'note:show',
            [
                "note" => "1"
            ]
        );
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach (['Id',  'Ticket id', 'Submitter', 'Text', 'Hidden', 'Viewed'] as $el) {
            $this->assertContains($el, $output);
        }
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'note:show',
            [
                "note" => "xxx"
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No matching note was found.", Artisan::output());
    }
}
