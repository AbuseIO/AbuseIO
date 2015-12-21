<?php

namespace AbuseIO\Console\Commands\Brand;

use Illuminate\Console\Command;
use AbuseIO\Models\Account;
use Carbon;

class DeleteCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'brand:delete
                            {--id= : Use the account id to delete it }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Deletes account (without confirmation!)';

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
     * @return boolean
     */
    public function handle()
    {
        if (empty($this->option('id'))) {
            $this->warn('no id argument was passed, try --help');
            return false;
        }

        /* @var $netblock  \AbuseIO\Models\Account|null */
        $account = Account::find($this->option("id"));
        if (null === $account) {
            $this->error(
                    sprintf('Unable to find account with id:%d', $this->option("id"))
                );
            return false;
        }

        if (!$account->delete()) {
            $this->error('Unable to delete account from the system');
            return false;
        }

        $this->info('The account has been deleted from the system');
        return true;
    }
}
