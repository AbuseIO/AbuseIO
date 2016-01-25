<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;

abstract class AbstractCreateCommand extends Command
{
    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    final public function handle()
    {
        $model = $this->getModelFromRequest();

        /** @var  $validation */
        $validation = $this->getValidator($model);
        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->error($message);
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
        $msg = sprintf('The %s has been created', $this->getAsNoun());
        if (array_key_exists('id', $model->getAttributes())) {
            $msg = sprintf('The %s has been created (id: %d)', $this->getAsNoun(), $model->id);
        }
        $this->info($msg);

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
                $this->getArgumentsList()
            );
    }

    final public function getName()
    {
        return sprintf('%s:%s', $this->getAsNoun(), $this->setCommandName());
    }

    /**
     * Default subcommand name.
     *
     * @return string
     */
    public function setCommandName()
    {
        return 'create';
    }

    final public function getDescription()
    {
        return sprintf('Creates a new %s', $this->getAsNoun());
    }

    abstract public function getArgumentsList();
}
