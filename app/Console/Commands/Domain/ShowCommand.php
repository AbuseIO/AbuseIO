<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Domain;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ShowCommand.
 */
class ShowCommand extends AbstractShowCommand
{
    /**
     * {@inherit docs}.
     */
    protected function getAsNoun()
    {
        return 'domain';
    }

    /**
     * {@inherit docs}.
     */
    protected function getAllowedArguments()
    {
        return ['id', 'name'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getFields()
    {
        return ['first_ip', 'last_ip', 'description', 'enabled'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getCollectionWithArguments()
    {
        return Domain::where('name', 'like', '%'.$this->argument('domain').'%')
            ->orWhere('id', $this->argument('domain'));
    }

    /**
     * {@inherit docs}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'domain',
                InputArgument::REQUIRED,
                'Use the id or name for a domain to show it.'
            ),
        ];
    }
}
