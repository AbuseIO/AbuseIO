<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\AbstractShowCommand2;
use AbuseIO\Models\Account;
use Symfony\Component\Console\Input\InputArgument;

class ShowCommand extends AbstractShowCommand2
{
    protected function transformObjectToTableBody($model)
    {
        return [
            ["Id", $model->id],
            ["Name", $model->name],
            ["Brand", $model->brand->name],
            ["Disabled", $model->disabled],
            ["Description", $model->description],
        ];
    }
    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "account";
    }

    /**
     * {@inherit docs}
     */
    protected function getAllowedArguments()
    {
        return ["id", "name"];
    }

    /**
     * {@inherit docs}
     */
    protected function getFields()
    {
        return ["id", "name","brand","disabled", "description"];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        return Account::where("name", "like", "%".$this->argument("account")."%")
            ->orWhere("id", $this->argument("account"));
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'account',
                InputArgument::REQUIRED,
                'Use the id or name for a account to show it.')
        ];
    }
}
