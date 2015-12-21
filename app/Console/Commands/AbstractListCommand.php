<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;


abstract class AbstractListCommand extends Command
{
    protected $headers = [];


    /**
     * Execute the console command.
     *
     * @return bool
     */
    public final function handle()
    {
        if (!empty($this->option('filter'))) {
            $list = $this->findWithCondition($this->option("filter"));
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
     * @param $filter
     * @return mixed
     */
    abstract protected function findAll();

    /**
     * @param $filter
     * @return mixed
     */
    abstract protected function getAsNoun();
}