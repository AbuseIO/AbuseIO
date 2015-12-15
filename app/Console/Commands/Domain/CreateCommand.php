<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Models\Domain;
use Illuminate\Console\Command;
use Carbon;
use AbuseIO\Models\User;
use Validator;

class CreateCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'domain:create
                            {--contact : e-mail address or id from contact }
                            {--name : domain name  }
                            {--enabled=false : true|false, Set the account to be enabled }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Creates a new domain';

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
        /**
         * TODO HAndle function sync with create command from NetBlocks.
         */

//        $domain = new Domain();
//
//        $domain->contact = User::find($this->option('contact'))?: User::where('email', '=', $this->option("contact"))->first();
//        $domain->name = $this->option('name');
//        $domain->enabled = $this->option('enabled') === "true" ? true : false;
//
//        /** @var  $validation */
//        $validation = Validator::make($domain->toArray(), Domain::createRules($domain));
//dd(get_class($validation));
//        if ($validation->fails()) {
//            foreach ($validation->messages()->all() as $message) {
//                $this->warn($message);
//            }
//
//            $this->error('Failed to create the domain due to validation warnings');
//
//            return false;
//        }
//
//        if (!$domain->save()) {
//            $this->error('Failed to save the domain into the database');
//
//            return false;
//        }
//
//        $this->info("The domain has been created");
//
//        return true;
    }
}
