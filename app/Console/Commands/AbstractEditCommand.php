<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class AbstractEditCommand.
 */
abstract class AbstractEditCommand extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;

    private $dirtyAttributes = [];

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

        /** @var $validation */
        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error(
                sprintf('Failed to edit the %s due to validation warnings', $this->getAsNoun())
            );

            return false;
        }

        if (!$model->update()) {
            $this->error(
                sprintf('Failed to save the %s into the database', $this->getAsNoun())
            );

            return false;
        }
        $this->info(
            sprintf('The %s has been updated', $this->getAsNoun())
        );

        return true;
    }

    abstract protected function getModelFromRequest();

    /**
     * @param $model
     *
     * @return bool
     */
    abstract protected function handleOptions($model);

    /**
     * @param $model
     *
     * @return mixed
     */
    abstract protected function getValidator($model);

    /**
     * @return mixed
     */
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

        return 'edit';
    }

    /**
     * @return string
     */
    final public function getDescription()
    {
        if (!empty($this->commandDescription)) {
            return $this->commandDescription;
        }

        return sprintf('Edit a %s', $this->getAsNoun());
    }

    /**
     * @param $model
     * @param $option
     * @param string $fieldType
     */
    protected function updateFieldWithOption($model, $option, $fieldType = 'string')
    {
        if ($model !== null) {
            if (array_key_exists($option, $model->getAttributes())) {
                if (!empty($this->option($option))) {
                    switch ($fieldType) {
                        case 'bool':
                        case 'boolean':
                            $value = castStringToBool($this->option($option));
                            break;
                        default:
                            $value = $this->option($option);
                            break;
                    }
                    $model->$option = $value;
                    $this->addToDirtyAttributes($option);
                }
            }
        }
    }

    /**
     * @param $attribute
     */
    private function addToDirtyAttributes($attribute)
    {
        $this->dirtyAttributes[] = $attribute;
    }

    /**
     * @param $updateRules
     *
     * @return array
     */
    public function getUpdateRulesForDirtyAttributes($updateRules)
    {
        return $this->returnOnlyKeysInFilter($this->dirtyAttributes, $updateRules);
    }

    /**
     * @param $model
     *
     * @return array
     */
    protected function getModelAsArrayForDirtyAttributes($model)
    {
        return $this->returnOnlyKeysInFilter($this->dirtyAttributes, $model->toArray());
    }

    /**
     * @param $keys
     * @param $array
     *
     * @return array
     */
    private function returnOnlyKeysInFilter($keys, $array)
    {
        $result = [];

        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $result[$key] = $array[$key];
            }
        }

        return $result;
    }

    /**
     * @param $model
     * @param $option
     */
    protected function updateBooleanFieldWithOption($model, $option)
    {
        $this->updateFieldWithOption($model, $option, 'bool');
    }

    /**
     * @return mixed
     */
    abstract public function getOptionsList();
}
