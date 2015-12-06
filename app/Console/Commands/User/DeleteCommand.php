<?php

namespace AbuseIO\Console\Commands\User;

use Illuminate\Console\Command;
use AbuseIO\Models\User;
use Carbon;

class DeleteCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'user:delete
                            {--user= : Use the user email or id to delete }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Deletes an user (without confirmation!)';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (empty($this->option('user'))) {
            $this->warn('no email or id argument was passed, try help');
            return false;
        }

        $user = false;
        if (!is_object($user)) {
            $user = User::where('email', $this->option('user'))->first();
        }

        if (!is_object($user)) {
            $user = User::find($this->option('user'));
        }

        if (!is_object($user)) {
            $this->error('Unable to find user with this criteria');
            return false;
        }

        if (!$user->delete()) {
            $this->error('Unable to delete user from the system');
            return false;
        }

        $this->info('The user has been deleted from the system');

        return true;
    }
}
