<?php

namespace tests;

use AbuseIO\Models\User;
use AbuseIO\Models\Role;
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

    protected $userId = 1; // use the default admin user defined in the db seed

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

        $this->user = User::find($this->userId);
        if (is_null($this->user)) {
            // Ensure a valid authenticatable admin user exists tied to system account
            $this->user = User::factory()->create(['account_id' => 1]);
            $adminRole = Role::find(1);
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
            ['--seed' => 'true]']
        );
    }
}
