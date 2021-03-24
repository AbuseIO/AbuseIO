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
use AbuseIO\Models\Job;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use tests\TestCase;

class CheckAccountTest extends TestCase
{
    use DatabaseTransactions;

    private $middleware;

    public function setUp(): void
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

        // assert callback is triggerd with request;
        $this->assertEquals('ok', $callback);
    }

    public function testModelHasNoAccountAccessMethodLog()
    {
        $this->actingAs(
            factory(User::class)->create()
        );

        $model = new Job();

        $r = new Request(['id' => $model->id]);
        // TODO this should have been the other loghandler
        Log::shouldReceive('notice')
            ->once()
            ->with('CheckAccount Middleware is called, with model_id [] for \AbuseIO\Models\[], which doesn\'t match the model_id format');

        $this->middleware->handle(
            $r,
            function ($request) {
            },
            $model
        );
    }

    public function testModelIdDoesNotRespond()
    {
        $this->actingAs(
            factory(User::class)->create()
        );

        $model = new Job();

        $r = new Request(['id' => $model->id]);
        // todo the arguments in the log method are not correct;
        Log::shouldReceive('notice')
            ->once()
            ->with('CheckAccount Middleware is called, with model_id [] for \AbuseIO\Models\[], which doesn\'t match the model_id format');

        $this->middleware->handle(
            $r,
            function ($request) {
            },
            $model
        );
    }
}
