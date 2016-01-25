<?php

namespace tests\Console\Commands\Contact;

use AbuseIO\Models\Contact;
use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    public function testWithoutArguments()
    {
        Artisan::call('contact:create');
        $output = Artisan::output();

        $this->assertContains('The reference field is required.', $output);
        $this->assertContains('The name field is required.', $output);
        $this->assertContains('The account id field is required.', $output);
        $this->assertContains('Failed to create the contact due to validation warnings', $output);
    }
}
