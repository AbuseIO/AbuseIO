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
    use ExitCodeHooks;

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
     * @return int
     */
    final public function handle(): int
    {
        /** @var Model $object */
        $object = $this->getObjectByArguments();

        if (!is_object($object)) {
            $this->error(
                sprintf('Unable to find %s with this criteria', $this->getAsNoun())
            );

            return $this->getNotFoundExitCode();
        }

        if ($this->stopDeleteAndThrowAnErrorBecauseRelations($object)) {
            return $this->getDeleteBlockedExitCode();
        }

        if (!$object->delete()) {
            $this->error(
                sprintf('Unable to delete %s from the system', $this->getAsNoun())
            );

            return $this->getDeleteFailedExitCode();
        }

        $this->info(
            sprintf('The %s has been deleted from the system', $this->getAsNoun())
        );

        return $this->getSuccessExitCode();
    }

    /**
     * @return string
     */
    final public function getName(): ?string
    {
        return sprintf('old:%s:%s', $this->getAsNoun(), $this->getCommandName());
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
    final public function getDescription(): string
    {
        if (!empty($this->commandDescription)) {
            return $this->commandDescription;
        }

        return sprintf('Deletes a %s (without confirmation!)', $this->getAsNoun());
    }

    /**
     * Hook to stop delete when relations exist.
     *
     * @param mixed $object
     *
     * @return bool
     */
    protected function stopDeleteAndThrowAnErrorBecauseRelations($object)
    {
        return false;
    }

    abstract protected function getAsNoun();

    abstract protected function getAllowedArguments();

    abstract protected function getObjectByArguments();

    abstract protected function defineInput();

    // Exit code hooks now provided by ExitCodeHooks trait
}
