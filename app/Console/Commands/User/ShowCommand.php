<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\User;
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
        return 'user';
    }

    /**
     * {@inherit docs}.
     */
    protected function getAllowedArguments()
    {
        return ['user'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getFields()
    {
        return [
            'id',
            'first_name',
            'last_name',
            'email',
            'account_id',
            'locale',
            'disabled',
        ];
    }

    /**
     * {@inherit docs}.
     */
    protected function getCollectionWithArguments()
    {
        return User::where('id', $this->argument('user'));
    }

    /**
     * {@inherit docs}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'user',
                InputArgument::REQUIRED,
                'Use the id for a user to show it.'
            ),
        ];
    }

    /**
     * {@inherit docs}.
     */
    protected function transformObjectToTableBody($model)
    {
        $result = parent::transformObjectToTableBody($model);

        $result = $this->hideProperty($result, 'Password');
        $result = $this->hideProperty($result, 'Account id');
        $result = $this->hideProperty($result, 'Remember token');

        $result[] = ['Account', $model->account->name];

        $roleList = [];
        foreach ($model->roles as $role) {
            $roleList[] = $role->description;
        }

        if ($roleList) {
            $roleCaption = 'Roles';
            if (count($roleList) === 1) {
                $roleCaption = 'Role';
            }
            $result[] = [$roleCaption, implode(PHP_EOL, $roleList)];
        }

        return $result;
    }
}
