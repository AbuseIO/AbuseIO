<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\InputDefinition;

abstract class AbstractDeleteCommand extends Command
{
    final protected function configure()
    {
        $this
            ->setName($this->getName())
            ->setDescription($this->getDescription())
            ->setDefinition(
                new InputDefinition(
                    $this->defineInput()
                )
            );
    }

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
        /** @var Model $object */
        $object = $this->getObjectByArguments();

        if (!is_object($object)) {
            $this->error(
                sprintf('Unable to find %s with this criteria', $this->getAsNoun())
            );
            return false;
        }

        if ($this->stopDeleteAndThrowAnErrorBecauseRelations($object)) {
            return false;
        }

        if (!$object->delete()) {
            $this->error(
                sprintf('Unable to delete %s from the system', $this->getAsNoun())
            );
            return false;
        }

        $this->info(
            sprintf('The %s has been deleted from the system', $this->getAsNoun())
        );
        return true;
    }

    final public function getName()
    {
        return sprintf('%s:%s', $this->getAsNoun(), $this->setCommandName());
    }

    /**
     * Default subcommand name
     *
     * @return string
     */
    public function setCommandName()
    {
        return 'delete';
    }

    final public function getDescription()
    {
        return sprintf('Deletes a %s (without confirmation!)', $this->getAsNoun());
    }

    protected function stopDeleteAndThrowAnErrorBecauseRelations($object)
    {
        return false;
    }

    abstract protected function getAsNoun();

    abstract protected function getAllowedArguments();

    abstract protected function getObjectByArguments();

    abstract protected function defineInput();
}
