<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Models\Domain;
use Illuminate\Console\Command;
use Carbon;
use AbuseIO\Models\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class CreateCommand extends Command
{

    /**
     * Configure the console command.
     */
    protected final function configure()
    {
        $this
            ->setName($this->getName())
            ->setDescription($this->getDescription())
            ->setDefinition(
                $this->getArguments()
            );
    }

    public function getArguments()
    {
        return new InputDefinition([
            new InputArgument('name', null, 'domain name'),
            new InputArgument('contact_id', null,  'the contact_id'),
            new InputArgument('enabled', null, 'true|false, Set the account to be enabled', false),
        ]);
    }

    public function getName()
    {
        return sprintf("%s:create", $this->getAsNoun());
    }

    public function getDescription()
    {
        return sprintf("Creates a new %s", $this->getAsNoun());
    }

    public function getAsNoun()
    {
        return "domain";
    }

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
        $domain = new Domain();

        $domain->contact_id = $this->argument('contact_id');
        $domain->name = $this->argument('name');
        $domain->enabled = $this->argument('enabled') === "true" ? true : false;

        /** @var  $validation */
        $validation = Validator::make($domain->toArray(), Domain::createRules($domain));
        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error('Failed to create the domain due to validation warnings');

            return false;
        }

        if (!$domain->save()) {
            $this->error('Failed to save the domain into the database');

            return false;
        }

        $this->info("The domain has been created");

        return true;
    }
}
