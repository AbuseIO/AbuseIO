<?php

namespace AbuseIO\Console\Commands\Domain;

use Illuminate\Console\Command;
use AbuseIO\Models\Netblock;
use Carbon;

class DeleteCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'domain:delete
                            {--id= : Use the netblock id to delete it }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Deletes domain (without confirmation!)';

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
            $this->warn('no id argument was passed, try help');
            return false;
        }

        /* @var $netblock  \AbuseIO\Models\Netblock|null */
        $netblock = Netblock::find($this->option("id"));
        if (null === $netblock) {
            $this->error(
                    sprintf('Unable to find netblock with id:%d', $this->option("id"))
                );
            return false;
        }

        if (!$netblock->delete()) {
            $this->error('Unable to delete netblock from the system');
            return false;
        }

        $this->info('The netblock has been deleted from the system');
        return true;
    }
}
