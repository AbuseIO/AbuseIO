<?php

namespace AbuseIO\Console\Commands\Brand;

use Illuminate\Console\Command;
use AbuseIO\Models\Brand;
use Carbon;

class DeleteCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'brand:delete
                            {--id= : Use the brand id to delete it }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Deletes brand (without confirmation!)';

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
        $brand = Brand::find($this->option("id"));
        if (null === $brand) {
            $this->error(
                    sprintf('Unable to find brand with id:%d', $this->option("id"))
                );
            return false;
        }

        if (!$brand->delete()) {
            $this->error('Unable to delete brand from the system');
            return false;
        }

        $this->info('The brand has been deleted from the system');
        return true;
    }
}
