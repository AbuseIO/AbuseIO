<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Contact;
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
        return 'contact';
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
        return ['id', 'reference', 'name', 'email', 'api_host', 'enabled', 'account_id'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getCollectionWithArguments()
    {
        return Contact::where('name', 'like', '%'.$this->argument('contact').'%')
            ->orWhere('id', $this->argument('contact'));
    }

    /**
     * {@inherit docs}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'contact',
                InputArgument::REQUIRED,
                'Use the id or name for a contact to show it.'
            ),
        ];
    }

    protected function transformObjectToTableBody($model)
    {
        $result = parent::transformObjectToTableBody($model);
        $result[] = ['Notification methods', $model->notificationMethodsAsString()];

        return $result;
    }
}
