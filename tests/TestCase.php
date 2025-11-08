<?php

namespace tests;

use AbuseIO\Models\Role;
use AbuseIO\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    // Dynamically resolve a valid admin user on the system account
    protected $userId = null;

    public $user;
    protected $startingObLevel;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        // Resolve the system account and an admin user under it
        $systemAccount = \AbuseIO\Models\Account::getSystemAccount();

        // Try to find any admin user under the system account
        $this->user = $systemAccount
            ? $systemAccount->admins()->first()
            : null;

        if (is_null($this->user)) {
            // Ensure a valid authenticatable admin user exists tied to system account
            $accountId = $systemAccount ? $systemAccount->id : 1;
            $this->user = User::factory()->create(['account_id' => $accountId]);
            // Attach the Admin role by name if available
            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole) {
                $this->user->roles()->syncWithoutDetaching([$adminRole->id]);
            }
        }

        return $app;
    }

    public function setUp(): void
    {
        parent::setUp();
        // Record the buffer level at the start of the test so we can safely clean up
        // only buffers opened by the test or application code, without touching PHPUnit's.
        $this->startingObLevel = ob_get_level();
    }

    protected function tearDown(): void
    {
        // Ensure test leaves no open output buffers it created,
        // but do not close PHPUnit's own output buffer.
        while (ob_get_level() > $this->startingObLevel) {
            ob_end_clean();
        }

        $this->beforeApplicationDestroyed(function () {
            DB::disconnect();
        });

        parent::tearDown();
    }

    protected function runMigration()
    {
        Artisan::call(
            'migrate:refresh',
            ['--seed' => true]
        );
    }
}
