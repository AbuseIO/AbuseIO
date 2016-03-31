<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class AbstractListCommand.
 */
abstract class AbstractListCommand extends Command
{
    protected $headers = [];

    protected $filterArguments = [];

    /**
     * Configure the console command.
     */
    final protected function configure()
    {
        $this
            ->setName($this->getName())
            ->setDescription($this->getDescription())
            ->addOption(
                'filter',
                null,
                InputOption::VALUE_OPTIONAL,
                $this->getFilterMessage()
            )->addOption(
                'json',
                null,
                InputOption::VALUE_NONE,
                'Output result as JSON'
            );
    }

    /**
     * @return string
     */
    protected function getFilterMessage()
    {
        return sprintf('Applies a filter on the %s %s', $this->getAsNoun(), $this->getParsedFilterArguments());
    }

    /**
     * @return string
     */
    private function getParsedFilterArguments()
    {
        return implode(' or ', $this->filterArguments);
    }

    /**
     * @return string
     */
    final public function getDescription()
    {
        if (!empty($this->commandDescription)) {
            return $this->commandDescription;
        }

        return sprintf('Shows a list of available %ss', $this->getAsNoun());
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

        return 'list';
    }

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
        $options = $this->option();

        if (!empty($options['filter'])) {
            $list = $this->findWithCondition($this->option('filter'));
        } else {
            $list = $this->findAll();
        }

        if (count($list) === 0) {
            $this->error(
                sprintf('No %s found for given filter.', $this->getAsNoun())
            );
        }
        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($list->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                $this->headers,
                $this->transformListToTableBody($list)
            );
        }

        return true;
    }

    /**
     * @param array $list
     *
     * @return array
     */
    abstract protected function transformListToTableBody($list);

    /**
     * @param $filter
     *
     * @return mixed
     */
    abstract protected function findWithCondition($filter);

    /**
     * @return mixed
     */
    abstract protected function findAll();

    /**
     * @return string
     */
    abstract protected function getAsNoun();
}
