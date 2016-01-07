<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\AbstractShowCommand2;
use AbuseIO\Models\Contact;
use Symfony\Component\Console\Input\InputArgument;

class ShowCommand extends AbstractShowCommand2
{
    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "contact";
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
        return ["id", "reference", "name", "email", "api_host", "api_key", "auto_notify", "enabled", "account_id"];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        return Contact::where("name", "like", "%".$this->argument("contact")."%")
            ->orWhere("id", $this->argument("contact"));
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'contact',
                InputArgument::REQUIRED,
                'Use the id or name for a contact to show it.')
        ];
    }
}
