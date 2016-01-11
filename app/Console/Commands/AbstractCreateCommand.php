<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractCreateCommand extends Command
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
                $this->getArgumentsList()
            );
    }

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public final function handle()
    {
        $model = $this->getModelFromRequest();

        /** @var  $validation */
        $validation = $this->getValidator($model);
        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error(
                sprintf('Failed to create the %s due to validation warnings', $this->getAsNoun())
            );

            return false;
        }

        if (!$model->save()) {
            $this->error(
                sprintf('Failed to save the %s into the database', $this->getAsNoun())
            );

            return false;
        }

        $this->info(
            sprintf("The %s has been created", $this->getAsNoun())
        );

        return true;
    }

    abstract public function getArgumentsList();

    public final function getName()
    {
        return sprintf("%s:create", $this->getAsNoun());
    }

    public final function getDescription()
    {
        return sprintf("Creates a new %s", $this->getAsNoun());
    }

    abstract public function getAsNoun();

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    abstract protected function getModelFromRequest();

    abstract protected function getValidator($model);
}
