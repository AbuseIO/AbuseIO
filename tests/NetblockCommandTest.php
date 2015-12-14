<?php


class NetblockCommandTest extends TestCase
{
    public function testNetBlockListCommand()
    {
        $exitCode = Artisan::call('netblock:list', []);

        $output = Artisan::output();

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Global internet", $output);
    }

    public function testNetBlockListCommandWithValidFilter()
    {
        $exitCode = Artisan::call('netblock:list', [
            "--filter" => "10.1.16.128"
        ]);

        $output = Artisan::output();

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Customer 6", $output);
        $this->assertNotContains("Global internet", $output);
    }

    public function testNetBlockShowWithValidContactFilter()
    {
        $exitCode = Artisan::call('netblock:show', [
            "--filter" => "Customer 6"
        ]);

        $output = Artisan::output();

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Customer 6", $output);
    }

    public function testNetBlockShowWithInvalidFilter()
    {
        $exitCode = Artisan::call('netblock:show', [
            "--filter" => "xxx"
        ]);
        $output = Artisan::output();

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No matching netblocks where found.", $output);
    }

    public function testNetBlockShowWithStartIpFilter()
    {
        $exitCode = Artisan::call('netblock:show', [
            "--filter" => "10.1.18.0"
        ]);
        $output = Artisan::output();

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Customer 8", $output);
    }

    public function testNetBlockShowWithStartEndFilter()
    {
        $exitCode = Artisan::call('netblock:show', [
            "--filter" => "10.1.16.195"
        ]);
        $output = Artisan::output();

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Customer 6", $output);
    }
}