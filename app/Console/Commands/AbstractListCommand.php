<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;



abstract class AbstractListCommand extends Command
{
    protected $headers = [];

    protected $filterArguments = [];


    /**
     * Configure the console command.
     */
    protected final function configure()
    {
        $this
            ->setName($this->getName())
            ->setDescription($this->getDescription())
            ->addOption(
                "filter",
                null,
                InputOption::VALUE_NONE,
                $this->getFilterMessage()
            )->addOption(
                "json",
                null,
                InputOption::VALUE_OPTIONAL,
                "use --json=true to output result as JSON"
            );

    }



    protected function getFilterMessage()
    {
        return sprintf("Applies a filter on the %s %s", $this->getAsNoun(), $this->getParsedFilterArguments());
    }

    private function getParsedFilterArguments()
    {
        return implode(" or ", $this->filterArguments);
    }

    /**
     * @return string
     */
    public final function getDescription()
    {
        return sprintf('Shows a list of available %ss', $this->getAsNoun());
    }

    /**
     * @return string
     */
    public final function getName()
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
    public final function handle()
    {
        $arguments = $this->argument();
        $options = $this->option();

        if (!empty($options['filter'])) {
            $list = $this->findWithCondition($this->option("filter"));
        } else {
            $list = $this->findAll();
        }

        if (count($list) === 0) {
            $this->error(
                sprintf("No %s found for given filter.", $this->getAsNoun())
            );
        } if ($this->option("json")) {
            echo $list->toJson();
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
     * @return array
     */
    abstract protected function transformListToTableBody($list);


    /**
     * @param $filter
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