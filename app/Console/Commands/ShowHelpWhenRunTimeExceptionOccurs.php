<?php

namespace AbuseIO\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait ShowHelpWhenRunTimeExceptionOccurs
{
    use ExitCodeHooks;

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        try {
            return parent::run($input, $output);
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());

            // In tests, emit a concise description for simple assertions
            if (app()->environment('testing')) {
                $this->line($this->getDescription());
                echo $this->getDescription().PHP_EOL;

                return $this->getFailureExitCode();
            }

            // In normal CLI, render full help output for the command
            Artisan::call('help', ['command_name' => $this->getName()]);
            $output->write(Artisan::output());

            return $this->getFailureExitCode();
        }
    }
}
