<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * Class AbstractDeleteCommand.
 */
abstract class AbstractDeleteCommand extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;

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

    /**
     * AbstractDeleteCommand constructor.
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

    /**
     * @return string
     */
    final public function getName()
    {
        return sprintf('%s:%s', $this->getAsNoun(), $this->getCommandName());
    }

    /**
     * Default subcommand name.
     *
     * @return string
     */
    final public function getCommandName()
    {
        if (!empty($this->commandName)) {
            return $this->commandName;
        }

        return 'delete';
    }

    /**
     * @return string
     */
    final public function getDescription()
    {
        if (!empty($this->commandDescription)) {
            return $this->commandDescription;
        }

        return sprintf('Deletes a %s (without confirmation!)', $this->getAsNoun());
    }

    /**
     * @param $object
     *
     * @return bool
     */
    protected function stopDeleteAndThrowAnErrorBecauseRelations($object)
    {
        return false;
    }

    /**
     * @return mixed
     */
    abstract protected function getAsNoun();

    /**
     * @return mixed
     */
    abstract protected function getAllowedArguments();

    /**
     * @return mixed
     */
    abstract protected function getObjectByArguments();

    /**
     * @return mixed
     */
    abstract protected function defineInput();
}
