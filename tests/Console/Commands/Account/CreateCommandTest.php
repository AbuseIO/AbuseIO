<?php

namespace tests\Console\Commands\Account;

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
        Artisan::call('account:create');
        $output = Artisan::output();

        $this->assertContains('The name field is required.', $output);
        $this->assertContains('Failed to create the account due to validation warnings', $output);
    }
}
