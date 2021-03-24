<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\Account;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Account */
    private $account;

    private function initDB()
    {
        $this->account = factory(Account::class)->create();
    }

    public function testWithoutId()
    {
        ob_start();
        Artisan::call('account:edit');
        $output = ob_get_clean();
        $this->assertStringContainsString('Edit a account', $output);
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
        $this->assertStringContainsString('Unable to find account with this criteria', Artisan::output());
    }

    public function testWithInvalidBrand()
    {
        $exitCode = Artisan::call(
            'account:edit',
            [
                'id'         => '1',
                '--brand_id' => '1000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Unable to find brand with this criteria', Artisan::output());
    }

    public function testName()
    {
        $this->assertEquals('Default', Account::find(1)->name);

        $exitCode = Artisan::call(
            'account:edit',
            [
                'id'     => '1',
                '--name' => 'somebogusstring',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('The account has been updated', Artisan::output());

        $account = Account::find(1);
        $this->assertEquals('somebogusstring', $account->name);

        $account->name = 'Default';
        $account->save();
    }

    public function testEnabled()
    {
        $this->assertFalse((bool) Account::find(1)->disabled);

        $exitCode = Artisan::call(
            'account:edit',
            [
                'id'         => '1',
                '--disabled' => 'true',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('The account has been updated', Artisan::output());

        $account = Account::find(1);

        $this->assertTrue((bool) $account->disabled);
        $account->disabled = false;
        $account->save();
    }

    public function testSetSystemAccount()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'account:edit',
            [
                'id'              => $this->account->id,
                '--systemaccount' => true,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('The account has been updated', Artisan::output());
        $this->assertTrue((bool) Account::find($this->account->id)->systemaccount);
    }
}
