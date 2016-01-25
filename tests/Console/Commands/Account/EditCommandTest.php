<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\Account;
use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments (missing: "id").
     */
    public function testWithoutId()
    {
        Artisan::call('account:edit');
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'account:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('Unable to find account with this criteria', Artisan::output());
    }

    public function testWithInvalidBrand()
    {
        $exitCode = Artisan::call(
            'account:edit',
            [
                'id' => '1',
                '--brand_id' => '1000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('Unable to find brand with this criteria', Artisan::output());
    }

    public function testName()
    {
        $this->assertEquals('default', Account::find(1)->name);

        $exitCode = Artisan::call(
            'account:edit',
            [
                'id' => '1',
                '--name' => 'somebogusstring',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('The account has been updated', Artisan::output());

        $account = Account::find(1);
        $this->assertEquals('somebogusstring', $account->name);

        $account->name = 'default';
        $account->save();
    }

    public function testEnabled()
    {
        $this->assertFalse((bool) Account::find(1)->disabled);

        $exitCode = Artisan::call(
            'account:edit',
            [
                'id' => '1',
                '--disabled' => 'true',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('The account has been updated', Artisan::output());

        $account = Account::find(1);

        $this->assertTrue((bool) $account->disabled);
        $account->disabled = false;
        $account->save();
    }
}
