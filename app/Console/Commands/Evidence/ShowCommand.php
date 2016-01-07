<?php

namespace AbuseIO\Console\Commands\Evidence;

use AbuseIO\Console\Commands\AbstractShowCommand2;
use AbuseIO\Models\Evidence;
use Symfony\Component\Console\Input\InputArgument;

class ShowCommand extends AbstractShowCommand2
{
    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "evidence";
    }

    /**
     * {@inherit docs}
     */
    protected function getAllowedArguments()
    {
        return ["id"];
    }

    /**
     * {@inherit docs}
     */
    protected function getFields()
    {
        return [
            'id',
            'filename',
            'sender',
            'subject'
        ];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        return Evidence::Where("id", $this->argument("evidence"));
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'evidence',
                InputArgument::REQUIRED,
                'Use the id for an evidence to show it.')
        ];
    }
}
