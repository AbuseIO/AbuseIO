<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractEditCommand extends Command
{
    /**
     * Create a new command instance
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
    final public function handle()
    {
        $model = $this->getModelFromRequest();

        /** @var  $validation */
        $validation = $this->getValidator($model);
        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error(
                sprintf('Failed to edit the %s due to validation warnings', $this->getAsNoun())
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
            sprintf("The %s has been updated", $this->getAsNoun())
        );

        return true;
    }

    abstract protected function getModelFromRequest();

    abstract protected function getValidator($model);

    abstract public function getAsNoun();

    /**
     * Configure the console command.
     */
    final protected function configure()
    {
        $this
            ->setName($this->getName())
            ->setDescription($this->getDescription())
            ->setDefinition(
                $this->getOptionsList()
            );
    }

    final public function getName()
    {
        return sprintf("%s:%s", $this->getAsNoun(), $this->setCommandName());
    }

    /**
     * Default subcommand name
     *
     * @return string
     */
    public function setCommandName()
    {
        return 'edit';
    }

    public final function getDescription()
    {
        return sprintf("Edit a %s", $this->getAsNoun());
    }

    protected function updateFieldWithOption($model, $option)
    {
        if (array_key_exists($option, $model->getAttributes())) {
            if (! empty($this->option($option))) {
                $model->$option = $this->option($option);
            }
        }
    }

    abstract public function getOptionsList();
}
