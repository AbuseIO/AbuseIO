<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputDefinition;


abstract class AbstractShowCommand2 extends Command
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
                new InputDefinition(
                    $this->defineInput()
                ));
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public final function handle()
    {
        $object = $this
            ->getCollectionWithArguments()
            ->first($this->getFields());

        if (!is_object($object)) {
            $this->error(
                sprintf("No matching %s was found.", $this->getAsNoun())
            );
        } else {
            $this->table(
                [],
                $this->transformObjectToTableBody($object)
            );
        }
        return true;
    }

    /**
     * @return string
     */
    public final function getName()
    {
        return sprintf('%s:show', $this->getAsNoun());
    }

    /**
     * @return string
     */
    public final function getDescription()
    {
        return sprintf('Shows a %s (without confirmation!)', $this->getAsNoun());
    }

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    abstract protected function getAsNoun();
    /**
     * @return array
     */

    abstract protected function getFields();
    /**
     * @return array
     */

    abstract protected function getAllowedArguments();

    /**
     * @return Collection
     */
    abstract protected function getCollectionWithArguments();
    /**
     * @return array
     */

    abstract protected function defineInput();

    /**
     * @param Model $queryResult
     * @return array
     */
    protected function transformObjectToTableBody(Model $model)
    {
        $result = [];
        foreach ($model->getAttributes() as $key => $value) {
            $heading = ucfirst(str_replace("_", " ", $key));
            $result[] = [$heading, $value];
        }
        return $result;
    }
}
