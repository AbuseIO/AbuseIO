<?php

namespace tests\Console\Commands\Job;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

class ShowCommandTest extends TestCase{

    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call('job:show', [
            "job" => "1"
        ]);
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach(['Id',  'Ticket id', 'Submitter', 'Text', 'Hidden', 'Viewed', 'Created at', 'Updated at', 'Deleted at'] as $el) {
            $this->assertContains($el,$output);
        }
    }



    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call('job:show', [
            "job" => "xxx"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No matching job was found.", Artisan::output());
    }


}
