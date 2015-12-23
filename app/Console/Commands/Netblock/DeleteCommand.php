<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Netblock;
use Symfony\Component\Console\Input\InputArgument;

class DeleteCommand extends AbstractDeleteCommand
{

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "netblock";
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
        return Netblock::find($this->argument("id"));
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
                'Use the id for a netblock to delete it.')
        );
    }


}
