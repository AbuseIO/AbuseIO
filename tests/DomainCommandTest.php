<?php

class DomainCommandTest extends TestCase{

    public function testNetBlockListCommandHeaders()
    {
        $exitCode = Artisan::call('domain:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ["Id", "Contact", "Name", "Enabled"];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testNetBlockListCommandAll()
    {
        $exitCode = Artisan::call('domain:list', []);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Customer 1", Artisan::output());
    }

    public function testNetBlockListCommandFilter()
    {
        $exitCode = Artisan::call('domain:list', [
            "--filter" => "domain1.com"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("domain1.com", Artisan::output());
        $this->assertNotContains("domain2.com", Artisan::output());
    }

    public function testNetBlockListCommandNotFoundFilter()
    {
        $exitCode = Artisan::call('domain:list', [
            "--filter" => "domain_unknown.com"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No domains found for given filter.", Artisan::output());
    }
}
