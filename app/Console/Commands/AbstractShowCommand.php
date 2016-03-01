<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Artisan;

/**
 * Class AbstractShowCommand
 * @package AbuseIO\Console\Commands
 */
abstract class AbstractShowCommand extends Command
{
    /**
     * Configure the console command.
     */
    final protected function configure()
    {
        $this
            ->setName($this->getName())
            ->setDescription($this->getDescription())
            ->setDefinition(
                new InputDefinition(
                    $this->defineInput()
                )
            )->addOption(
                'json',
                null,
                InputOption::VALUE_NONE,
                'Output result as JSON'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        try {
            return parent::run($input, $output);
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());
            Artisan::call(
                $this->getName(),
                [
                "--help" => "true",
                ]
            );
            echo Artisan::output();

            return false;
        }
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    final public function handle()
    {
        $object = $this
            ->getCollectionWithArguments()
            ->first();

        if (!is_object($object)) {
            $this->error(
                sprintf('No matching %s was found.', $this->getAsNoun())
            );
        } elseif ($this->option('json')) {
            echo $object->toJson();
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
        return 'show';
    }

    /**
     * @return string
     */
    final public function getDescription()
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
     * @param $model
     *
     * @return array
     */
    protected function transformObjectToTableBody($model)
    {
        $result = [];
        foreach ($model->getAttributes() as $key => $value) {
            $heading = ucfirst(str_replace('_', ' ', $key));
            $result[] = [$heading, $value];
        }

        return $result;
    }

    protected function hideProperty($resultSet, $property)
    {
        foreach ($resultSet as $key => $result) {
            if ($result[0] === $property) {
                unset($resultSet[$key]);
            }
        }
        return $resultSet;
    }

}
