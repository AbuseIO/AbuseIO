<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\User;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class DeleteCommand.
 */
class DeleteCommand extends AbstractDeleteCommand
{
    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAllowedArguments()
    {
        return ['user'];
    }

    /**
     * {@inheritdoc}.
     */
    protected function getObjectByArguments()
    {
        $user = false;
        if (!is_object($user)) {
            $user = User::where('email', $this->argument('user'))->first();
        }

        if (!is_object($user)) {
            $user = User::find($this->argument('user'));
        }

        return $user;
    }

    /**
     * {@inheritdoc}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'user',
                InputArgument::REQUIRED,
                'Use the name or email for a user to delete it.'
            ),
        ];
    }
}
