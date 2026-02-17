<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testEditAccountCommandShouldFailWithoutId(): void
    {
        $exitCode = Artisan::call('account:edit');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id")', $output);
        $this->assertStringContainsString('Edits an existing account', $output);
    }

    #[group('functional')]
    public function testEditAccountCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'account:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Account not found', Artisan::output());
    }

    #[group('functional')]
    public function testEditAccountCommandShouldFailWithInvalidBrand(): void
    {
        $exitCode = Artisan::call(
            'account:edit',
            [
                'id'         => '1',
                '--brand_id' => '1000',
            ]
        );
        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Brand not found', Artisan::output());
    }

    #[group('functional')]
    public function testEditAccountCommandShouldPassWithDifferentName(): void
    {
        $account = Account::create([
            'name'          => 'Default User Outdated',
            'description'   => 'test description',
            'brand_id'      => 1,
            'disabled'      => 0,
            'token'         => generateApiToken(),
            'systemaccount' => 0,
        ]);

        $exitCode = Artisan::call(
            'account:edit',
            [
                'id'     => $account->id,
                '--name' => 'Changed Name',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The account has been updated successfully.', Artisan::output());
    }

    #[group('functional')]
    public function testEditAccountCommandShouldPassSettingDisabledToTrue(): void
    {
        $this->assertFalse((bool) Account::find(1)->disabled);

        $exitCode = Artisan::call(
            'account:edit',
            [
                'id'         => '1',
                '--disabled' => 'true',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The account has been updated', Artisan::output());
    }

    #[group('functional')]
    public function testEditAccountCommandShouldPassSettingSystemAccountToTrue(): void
    {
        $firstAccount = Account::find(1)->first();
        $firstAccount->systemaccount = 0;
        $this->assertFalse((bool) $firstAccount->systemaccount);
        $firstAccount->save();
        $account = Account::create([
            'name'          => 'Default User Outdated',
            'description'   => 'test description',
            'brand_id'      => 1,
            'disabled'      => 0,
            'token'         => generateApiToken(),
            'systemaccount' => 0,
        ]);

        $exitCode = Artisan::call(
            'account:edit',
            [
                'id'              => $account->id,
                '--name'          => 'Default User Updated',
                '--systemaccount' => true,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The account has been updated', Artisan::output());
        $this->assertTrue((bool) Account::find($account->id)->toArray()['systemaccount']);
    }
}
