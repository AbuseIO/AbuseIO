<?php

namespace tests\Rules;

use AbuseIO\Models\Account;
use AbuseIO\Rules\UniqueFlag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;
use Validator;

/**
 * Class UniqueFlagTest.
 *
 * @note While migrating, the database will be filled with one account in the database that already has the flag of systemaccount set to true.
 *       The database can only have one system account, so tests that try to create an account with systemaccount true will fail.
 */
class UniqueFlagTest extends TestCase
{
    use RefreshDatabase;

    #[group('unit')]
    public function testValidationShouldFailWithBooleanTrue(): void
    {
        $account = Account::make([
            'systemaccount' => 1,
        ]);

        $validator = Validator::make($account->toArray(), ['systemaccount' => new UniqueFlag('accounts', 'systemaccount')]);

        $this->assertTrue($validator->fails());
    }

    #[group('unit')]
    public function testValidationShouldSucceedWithBooleanFalse(): void
    {
        $account = Account::make([
            'systemaccount' => 0,
        ]);

        $validator = Validator::make($account->toArray(), ['systemaccount' => new UniqueFlag('accounts', 'systemaccount')]);

        $this->assertFalse($validator->fails());
    }

    #[group('unit')]
    public function testValidationShouldFailWithStringVersionOfTrue(): void
    {
        $account = Account::make([
            'systemaccount' => 'true',
        ]);

        $validator = Validator::make($account->toArray(), ['systemaccount' => new UniqueFlag('accounts', 'systemaccount')]);

        $this->assertTrue($validator->fails());
    }

    #[group('unit')]
    public function testValidationShouldSucceedWithStringVersionOfFalse(): void
    {
        $account = Account::make([
            'systemaccount' => 'false',
        ]);

        $validator = Validator::make($account->toArray(), ['systemaccount' => new UniqueFlag('accounts', 'systemaccount')]);

        $this->assertFalse($validator->fails());
    }

    #[group('unit')]
    public function testValidationShouldFailWithInvalidBooleanValue(): void
    {
        $account = Account::make([
            'systemaccount' => 'Test',
        ]);

        $validator = Validator::make($account->toArray(), ['systemaccount' => new UniqueFlag('accounts', 'systemaccount')]);

        $this->assertTrue($validator->fails());
    }

    #[group('unit')]
    public function testValidationRuleShouldFailWithNullValue(): void
    {
        $account = Account::make([
            'systemaccount' => null,
        ]);

        $validator = Validator::make($account->toArray(), ['systemaccount' => new UniqueFlag('accounts', 'systemaccount')]);

        $this->assertTrue($validator->fails());
    }
}
