<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 11/11/16
 * Time: 15:02.
 */
namespace tests\Http\Middleware;

use AbuseIO\Http\Middleware\CheckAccount;
use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use tests\TestCase;

class CheckAccountTest extends TestCase
{
    use DatabaseTransactions;

    private $middleware;

    public function setUp()
    {
        parent::setUp();
        $this->middleware = new CheckAccount();
    }

    public function testUserIsSystemAccount()
    {
        $this->actingAs(User::find(1));

        $model = factory(Account::class)->create();

        $callback = $this->middleware->handle(
            new Request(),
            function ($request) {
                return 'ok';
            },
            $model
        );

        $this->assertEquals(
            'ok',
            $callback
        );
    }

    public function testUserIsNotOwner()
    {
        $userNotInDB = factory(User::class)->create();

        $this->actingAs($userNotInDB);

        $model = factory(Account::class)->create();

        $r = new Request(['id' => $model->id]);


        $callback = $this->middleware->handle(
            $r,
            function ($request) {
                return 'ok';
            },
            $model
        );

        $this->isInstanceOf(RedirectResponse::class, $callback);
    }
}
