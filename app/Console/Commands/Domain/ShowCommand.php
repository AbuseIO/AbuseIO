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
        // Show actual Domain attributes instead of Netblock fields
        return ['id', 'contact', 'name', 'enabled'];
    }

    /**
     * {@inherit docs}.
     */
    protected function transformObjectToTableBody($model)
    {
        $rows = [];

        // Id
        $rows[] = ['Id', $model->id];

        // Contact displayed as reference and/or email
        $contactText = '';
        if ($model->contact) {
            $parts = [];
            if (!empty($model->contact->reference)) {
                $parts[] = $model->contact->reference;
            }
            if (!empty($model->contact->email)) {
                $parts[] = $model->contact->email;
            }
            $contactText = implode(' / ', $parts);
        }
        $rows[] = ['Contact', $contactText];

        // Name
        $rows[] = ['Name', $model->name];

        // Enabled
        $rows[] = ['Enabled', $model->enabled];

        return $rows;
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
