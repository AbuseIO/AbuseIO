<?php

namespace tests\Console\Commands\Evidence;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

class ShowCommandTest extends TestCase{

    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call('evidence:show', [
            "evidence" => "1"
        ]);
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach(['Id', 'Filename', 'Sender', 'Subject', 'Created at'] as $el) {
            $this->assertContains($el,$output);
        }
    }



    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call('evidence:show', [
            "evidence" => "xxx"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No matching evidence was found.", Artisan::output());
    }


}
