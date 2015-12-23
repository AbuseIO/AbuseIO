<?php

namespace AbuseIO\Console\Commands\Brand;

use Symfony\Component\Console\Input\InputArgument;
use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Brand;


class DeleteCommand extends AbstractDeleteCommand
{
    protected function getAsNoun()
    {
        return "brand";
    }

    protected function getAllowedArguments()
    {
        return ["id"];
    }

    protected function getObjectByArguments()
    {
        return Brand::find($this->argument("id"));
    }

    protected function defineInput()
    {
        return array(
            new InputArgument(
                'id',
                InputArgument::REQUIRED,
                'Use the id for a brand to delete it.'
            )
        );
    }


}
