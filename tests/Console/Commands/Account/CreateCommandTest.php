<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    public function testWithoutArguments()
    {

//        Artisan::call('account:create');
//        $output = Artisan::output();
//
//        $this->assertContains('account:create', $output);
    }

    public function testCreateValid()
    {
        $brand = factory(Brand::class)->create();

        Artisan::call('account:create', [
            'name'     => 'test_dummy',
            'brand_id' => $brand->id,
        ]);
        $output = Artisan::output();

        $this->assertContains('The account has been created', $output);

        $account = Account::where('name', 'test_dummy')->first();

        $this->assertEquals('', $account->token);

        $account->forceDelete();
        $brand->forceDelete();
    }

    public function testCreateValidWithApiToken()
    {
        $brand = factory(Brand::class)->create();

        Artisan::call('account:create', [
            'name'           => 'test_dummy',
            'brand_id'       => $brand->id,
            '--with_api_key' => true,
        ]);
        $output = Artisan::output();

        $this->assertContains('The account has been created', $output);

        $account = Account::where('name', 'test_dummy')->first();

        $this->assertNotNull($account->token);

        $account->forceDelete();
        $brand->forceDelete();
    }
}
