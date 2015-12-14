<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Models\Netblock;
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
    protected $signature = 'netblock:create
                            {--contact : e-mail address or id from contact }
                            {--first_ip : Start Ip address from netblock  }
                            {--last_ip : End Ip addres from netblock }
                            {--description : Description }
                            {--enabled=false : true|false, Set the account to be enabled }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Creates a new netblock';

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
        $netblock = new Netblock();

        $contact = $this->findUserByIdOrEmail($this->option("contact"));

        if (null === $contact) {
            $this->error("Failed to find contact, could\'nt save netblock");
            return false;
        }
        $netblock->contact()->associate($contact);
        $netblock->first_ip = $this->option('first_ip');
        $netblock->last_ip = $this->option('last_ip');
        $netblock->description = $this->option('description');
        $netblock->enabled = $this->option('enabled') === "true" ? true : false;


        $validation = Validator::make($netblock->toArray(), Netblock::createRules($netblock));

        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error('Failed to create the netblock due to validation warnings');

            return false;
        }

        if (!$netblock->save()) {
            $this->error('Failed to save the netblock into the database');

            return false;
        }

        $this->info("The netblock has been created");

        return true;
    }

    /**
     * @return mixed
     */
    private function findUserByIdOrEmail($param)
    {
        $contact = User::find($param) ?:
            User::where('email', '=', $param)->first();
        return $contact;
    }
}

