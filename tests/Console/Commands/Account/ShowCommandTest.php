<?php

namespace tests\Console\Commands\Account;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

class ShowCommandTest extends TestCase{

    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call('account:show', [
            "--id" => "1"
        ]);
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach(["Name", "default", "Brand", "Id", "Description"] as $el) {
            $this->assertContains($el,$output);
        }
    }

    public function testWithValidNameFilter()
    {
        $exitCode = Artisan::call('account:show', [
            "--name" => "Account 2"
        ]);
        $this->assertEquals($exitCode, 0);
        $this->assertContains("Account",Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call('account:show', [
            "--id" => "xxx"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No matching accounts where found.", Artisan::output());
    }


}
