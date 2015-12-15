<?php

namespace AbuseIO\Console\Commands\Netblock;

use Illuminate\Console\Command;
use AbuseIO\Models\User;
use AbuseIO\Models\Netblock;
use Validator;
use Carbon;

class EditCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'netblock:edit
                            {--id : Id for the block you wish to update }
                            {--contact : e-mail address or id from contact }
                            {--first_ip : Start Ip address from netblock  }
                            {--last_ip : End Ip addres from netblock }
                            {--description : Description }
                            {--enabled : Set the account to be enabled }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Changes information from a netblock';

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
            $this->warn('The required id argument was not passed, try help');
            return false;
        }
        /** @var Netblock|null $netblock */
        $netblock = Netblock::find($this->option('id'));

        if (null === $netblock) {
            $this->error('Unable to find netblock with this criteria');
            return false;
        }

        if (!empty($this->option("contact"))) {
            /** @var User|null $user */
            $user = User::find($this->option('contact'))?: User::where('email', '=', $this->option("contact"))->first();
            if (null === $user) {
                $this->error("Unable to find contact with this criteria");
                return false;
            }
            $netblock->contact()->associate($user);
        }

        $stringOptions = ["first_ip", "last_ip", "description"];
        foreach ($stringOptions as $option) {
            if (!empty($this->option($option))) {
                $netblock->$option = $this->option($option);
            }
        }

        if (!empty($this->option("enabled"))) {
            $netblock->enabled = castStringToBool($this->option("enabled"));
        }


        $validation = Validator::make($netblock->toArray(), Netblock::updateRules($netblock));

        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error('Failed to create the user due to validation warnings');

            return false;
        }
        $netblock->save();

        $this->info("User has been successfully updated");

        return true;
    }
}
