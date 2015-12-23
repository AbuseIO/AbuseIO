<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;


abstract class AbstractShowCommand extends Command
{
    protected $headers = [];


    /**
     * Create a new command instance.
     * @return void
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
        if (!empty($this->argument('name'))) {
            $list = $this->findWithCondition($this->argument("name"));
        } else {
            $list = $this->findAll();
        }

        if (count($list) === 0) {
            $this->error(
                sprintf("No %s found for given filter.", $this->getAsNoun())
            );
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