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
        if (null === $model) {
            $this->error(
                sprintf('Unable to find %s with this criteria', $this->getAsNoun())
            );
            return false;
        }

        if (!$this->handleOptions($model)) {
            return false;
        }

        $validation = $this->getValidator($model);

        /** @var  $validation */

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

    /**
     * @param $model
     * @return bool
     */
    abstract protected function handleOptions($model);

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

    protected function updateFieldWithOption($model, $option, $fieldType='string')
    {
        if ($model !== null) {
            if (array_key_exists($option, $model->getAttributes())) {
                if (!empty($this->option($option))) {
                    switch ($fieldType) {
                        case "bool":
                        case "boolean":
                            $value = castStringToBool($this->option($option));
                            break;
                        default:
                            $value = $this->option($option);
                            break;
                    }
                    $model->$option = $value;
                }
            }
        }
    }

    protected function updateBooleanFieldWithOption($model, $option)
    {
        $this->updateFieldWithOption($model, $option, "bool");
    }

    abstract public function getOptionsList();
}
