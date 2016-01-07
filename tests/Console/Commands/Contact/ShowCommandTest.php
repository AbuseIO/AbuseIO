<?php

namespace tests\Console\Commands\Contact;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

class ShowCommandTest extends TestCase{

    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call('contact:show', [
            "contact" => "1"
        ]);
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach(['Reference', 'Name', 'Email', 'Api host', 'Api key', 'Auto notify', 'Enabled'] as $el) {
            $this->assertContains($el,$output);
        }
    }

    public function testWithValidNameFilter()
    {
        $exitCode = Artisan::call('contact:show', [
            "contact" => "Global internet"
        ]);
        $this->assertEquals($exitCode, 0);
        $this->assertContains("internet@local.lan",Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call('contact:show', [
            "contact" => "xxx"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No matching contact was found.", Artisan::output());
    }


}
