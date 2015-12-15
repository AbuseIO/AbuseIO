<?php

namespace AbuseIO\Console\Commands\Domain;

use Illuminate\Console\Command;
use AbuseIO\Models\User;
use AbuseIO\Models\Domain;
use Validator;
use Carbon;

class EditCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'domain:edit
                            {--id : Id for the block you wish to update }
                            {--contact : e-mail address or id from contact }
                            {--name : domain name  }
                            {--enabled : Set the account to be enabled }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Changes information from a domain';

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
        /** @var Domain|null $domain */
        $domain = Domain::find($this->option('id'));

        if (null === $domain) {
            $this->error('Unable to find domain with this criteria');
            return false;
        }

        if (!empty($this->option("contact"))) {
            /** @var User|null $user */
            $user = User::find($this->option('contact'))?: User::where('email', '=', $this->option("contact"))->first();
            if (null === $user) {
                $this->error("Unable to find contact with this criteria");
                return false;
            }
            $domain->contact()->associate($user);
        }

        if (!empty($this->option("name"))) {
            $domain->name = $this->option("name");
        }

        if (!empty($this->option("enabled"))) {
            $domain->enabled = castStringToBool($this->option("enabled"));
        }

        $validation = Validator::make($domain->toArray(), Domain::updateRules($domain));

        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error('Failed to create the domain due to validation warnings');

            return false;
        }
        $domain->save();

        $this->info("Domain has been successfully updated");

        return true;
    }
}
