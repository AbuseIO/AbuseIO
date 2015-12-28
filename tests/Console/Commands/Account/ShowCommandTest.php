<?php

namespace tests\Console\Commands\Account;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

class ShowCommandTest extends TestCase{

    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call('account:show', [
            "account" => "1"
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
            "account" => "Account 2"
        ]);
        $this->assertEquals($exitCode, 0);
        $this->assertContains("Account",Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call('account:show', [
            "account" => "xxx"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No matching account was found.", Artisan::output());
    }


}
