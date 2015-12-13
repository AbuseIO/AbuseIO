<?php


class NetblockCommandTest extends TestCase
{


    public function testNetBlockListCommand()
    {
        $exitCode = Artisan::call('netblock:list', []);

        $output = Artisan::output();

        // TODO findout how the exitCode works so I can test for it.
        //$this->assertEquals($exitCode, 0);

        $this->assertContains("Global internet", $output);
    }

    public function testNetBlockListCommandWithValidFilter()
    {
        $exitCode = Artisan::call('netblock:list', [
            "--filter" => "10.1.16.128"
        ]);

        $output = Artisan::output();

        // TODO findout how the exitCode works so I can test for it.
        //$this->assertEquals($exitCode, 0);
        $this->assertContains("Customer 6", $output);

        $this->assertNotContains("Global internet", $output);
    }


}