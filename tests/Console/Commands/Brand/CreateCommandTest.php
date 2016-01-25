<?php

namespace tests\Console\Commands\Brand;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    public function testWithoutArguments()
    {
        Artisan::call('brand:create');
        $output = Artisan::output();

        $this->assertContains('The name field is required.', $output);
        $this->assertContains('The company name field is required.', $output);
        $this->assertContains('The introduction text field is required.', $output);
        $this->assertContains('Failed to create the brand due to validation warnings', $output);
    }
}
