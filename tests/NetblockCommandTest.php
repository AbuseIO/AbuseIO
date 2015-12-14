<?php

class NetblockCommandTest extends TestCase{

    public function testNetBlockListCommand()
    {
        $exitCode = Artisan::call('netblock:list', []);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Global internet", Artisan::output());
    }

    public function testNetBlockListCommandWithValidFilter()
    {
        $exitCode = Artisan::call('netblock:list', [
            "--filter" => "10.1.16.128"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Customer 6", Artisan::output());
        $this->assertNotContains("Global internet", Artisan::output());
    }

    public function testNetBlockShowWithValidContactFilter()
    {
        $exitCode = Artisan::call('netblock:show', [
            "--filter" => "Customer 6"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Customer 6", Artisan::output());
    }

    public function testNetBlockShowWithInvalidFilter()
    {
        $exitCode = Artisan::call('netblock:show', [
            "--filter" => "xxx"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No matching netblocks where found.", Artisan::output());
    }

    public function testNetBlockShowWithStartIpFilter()
    {
        $exitCode = Artisan::call('netblock:show', [
            "--filter" => "10.1.18.0"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Customer 8",  Artisan::output());
    }

    public function testNetBlockShowWithStartEndFilter()
    {
        $exitCode = Artisan::call('netblock:show', [
            "--filter" => "10.1.16.195"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Customer 6", Artisan::output());
    }

    public function testNetBlockDeleteValid()
    {
        $exitCode = Artisan::call('netblock:delete', [
            "--id" => "1"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("netblock has been deleted", Artisan::output());
        /**
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        $this->seed('NetblocksTableSeeder');
    }

    public function testNetBlockDeleteInvalidId()
    {
        $exitCode = Artisan::call('netblock:delete', [
            "--id" => "1000"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Unable to find netblock", Artisan::output());
    }


}
