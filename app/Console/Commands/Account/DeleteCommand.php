<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Account;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeleteCommand
 * @package AbuseIO\Console\Commands\Account
 */
class DeleteCommand extends AbstractDeleteCommand
{
    /**
     * {@inheritdoc }
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        try {
            return parent::run($input, $output);
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());
            $this->error(
                sprintf("Please try %s --help", $this->getName())
            );

            return false;
        }
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "account";
    }

    /**
     * {@inheritdoc }
     */
    protected function getAllowedArguments()
    {
        return ["id"];
    }

    /**
     * {@inheritdoc }
     */
    protected function getObjectByArguments()
    {
        return Account::find($this->argument("id"));
    }

    /**
     * {@inheritdoc }
     */
    protected function defineInput()
    {
        return array(
            new InputArgument(
                'id',
                InputArgument::REQUIRED,
                'Use the id for a account to delete it.'
            )
        );
    }
}
