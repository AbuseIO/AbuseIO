<?php

namespace AbuseIO\Console\Commands;

use Artisan;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait ShowHelpWhenRunTimeExceptionOccurs
{
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
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
                    '--help' => 'true',
                ]
            );

            echo Artisan::output();

            return false;
        }
    }
}
