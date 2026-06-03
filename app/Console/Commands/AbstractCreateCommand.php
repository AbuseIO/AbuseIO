<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class AbstractCreateCommand.
 */
abstract class AbstractCreateCommand extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Configure the console command.
     */
    protected function configure()
    {
        $this
            ->setName($this->getName())
            ->setDescription($this->getDescription())
            ->setDefinition(
                $this->getArgumentsList()
            );
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return sprintf('old:%s:%s', $this->getAsNoun(), $this->getCommandName());
    }

    /**
     * Default subcommand name.
     *
     * @return string
     */
    public function getCommandName()
    {
        if (!empty($this->commandName)) {
            return $this->commandName;
        }

        return 'create';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if (!empty($this->commandDescription)) {
            return $this->commandDescription;
        }

        return sprintf('Creates a new %s', $this->getAsNoun());
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    final public function handle(): int
    {
        $model = $this->getModelFromRequest();

        /** @var $validation */
        $validation = $this->getValidator($model);
        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->error($message);
            }

            $this->error(
                sprintf('Failed to create the %s due to validation warnings', $this->getAsNoun())
            );

            return $this->getValidationFailedExitCode();
        }

        if (!$model->save()) {
            $this->error(
                sprintf('Failed to save the %s into the database', $this->getAsNoun())
            );

            return $this->getSaveFailedExitCode();
        }
        $msg = sprintf('The %s has been created', $this->getAsNoun());
        if (array_key_exists('id', $model->getAttributes())) {
            $msg = sprintf('The %s has been created (id: %d)', $this->getAsNoun(), $model->id);
        }
        $this->info($msg);

        return $this->getSuccessExitCode();
    }

    abstract public function getArgumentsList();

    abstract protected function getModelFromRequest();

    abstract protected function getValidator($model);

    abstract protected function getAsNoun();

    // Exit code hooks now provided by ExitCodeHooks trait
}
