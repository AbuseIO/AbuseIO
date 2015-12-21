<?php

namespace tests\Console\Commands\Brand;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

class ShowCommandTest extends TestCase{

    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call('brand:show', [
            "--id" => "1"
        ]);
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach(["Name", "Company name", "Introduction text", "Id"] as $el) {
            $this->assertContains($el, $output);
        }
    }

    public function testWithValidNameFilter()
    {
        $exitCode = Artisan::call('brand:show', [
            "--name" => "default"
        ]);
        $this->assertEquals($exitCode, 0);
        $this->assertContains("AbuseIO",Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call('brand:show', [
            "--id" => "xxx"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No matching brand was found.", Artisan::output());
    }


}
