<?php

use Illuminate\Support\Facades\Artisan;

class NetblockCommandTest extends TestCase{

    /**
     * List test
     */
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

    /**
     * Show test
     */
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

    /**
     * Delete Test
     */
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


    /**
     * Edit test
     */
    public function testNetBlockEditWithoutId()
    {
        $exitCode = Artisan::call('netblock:edit');
        $this->assertEquals($exitCode, 0);
        $this->assertContains("The required id argument was not passed, try help", Artisan::output());
    }

    public function testNetBlockEditWithInvalidId()
    {
        $exitCode = Artisan::call('netblock:edit', [
            "--id" => "10000"
        ]);
        $this->assertEquals($exitCode, 0);
        $this->assertContains("Unable to find netblock with this criteria", Artisan::output());
    }

    public function testNetBlockEditWithInvalidContact()
    {
        $exitCode = Artisan::call('netblock:edit', [
            "--id" => "1",
            "--contact" => "1000"
        ]);
        $this->assertEquals($exitCode, 0);
        $this->assertContains("Unable to find contact with this criteria", Artisan::output());
    }

    public function testNetBlockEditEnabled()
    {
        $exitCode = Artisan::call('netblock:edit', [
            "--id" => "1",
            "--enabled" => "false"
        ]);
        $this->assertEquals($exitCode, 0);
        $this->assertContains("Netblock has been successfully updated", Artisan::output());
        /**
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        $this->seed('NetblocksTableSeeder');
    }
    
    public function testNetblockCreate()
    {
        $exitCode = Artisan::call("netblock:create",[
            "--contact" => "1",
            "--first_ip" => "192.168.0.0",
            "--last_ip" => "192.168.255.255",
            "--description" => "16-bit block",
            "--enabled" => "true"
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertContains("created", Artisan::output());

        $this->seed("NetblocksTableSeeder");
    }

    public function testNetblockCreateWithoutParams()
    {
        $exitCode = Artisan::call("netblock:create");
        $this->assertEquals(0, $exitCode);
        $this->assertContains("Failed to find contact, could\'nt save netblock", Artisan::output());
    }

    public function testNetblockCreateWithoutParamsButValidUser()
    {
        $exitCode = Artisan::call("netblock:create", [
            "--contact" => "1"
        ]);
        $this->assertEquals(0, $exitCode);
        $this->assertContains("The first ip must be a valid IP address.\nThe last ip must be a valid IP address.\nFailed to create the netblock due to validation warnings\n",
            Artisan::output()
        );
    }
}

