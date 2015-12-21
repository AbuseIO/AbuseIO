<?php

namespace AbuseIO\Console\Commands\Brand;

use Illuminate\Console\Command;
use AbuseIO\Models\Account;
use Carbon;

class ShowCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'brand:show
                            {--id= : Use the id to show details }
                            {--name= : Use the name show details }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows the details of an account';

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
        if (empty($this->option('id')) && empty($this->option('name'))) {
            $this->warn('Pass a name or id via the filter argument or try --help');
            return false;
        }
        /* @var $account  \AbuseIO\Models\Account|null */
        $account = $this->findByNameOrId($this->option("name"), $this->option("id"));

        if (null !== $account) {
            $this->table([], $this->transformNetblockToTableBody($account));
        } else {
            $this->warn("No matching accounts where found.");
        }
        return true;
    }

    /**
     * @param Account $account
     * @return array
     */
    private function transformNetblockToTableBody(Account $account)
    {
        return  [
            ["Id", $account->id],
            ["Name", $account->name],
            ["Brand", $account->brand->name],
            ["Description", $account->description]
        ];
    }

    /**
     * @param $name
     * @param $id
     * @return Account|null
     */
    private function findByNameOrId($name, $id)
    {
        if ($name) {
            return $this->findByName($name);
        }
        return $this->findById($id);
    }


    /**
     * @param $id
     * @return Account|null
     */
    private function findById($id)
    {
        return Account::find($id);
    }

    /**
     * @param $name
     * @return Account|null
     */
    private function findByName($name)
    {
        return Account::where("name", "like", "%".$name."%")->first();
    }
}
