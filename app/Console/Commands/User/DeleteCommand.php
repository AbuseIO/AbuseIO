<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\User;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class DeleteCommand
 * @package AbuseIO\Console\Commands\User
 */
class DeleteCommand extends AbstractDeleteCommand
{
    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "user";
    }

    /**
     * {@inheritdoc }
     */
    protected function getAllowedArguments()
    {
        return ["name", "email"];
    }

    /**
     * {@inheritdoc }
     */
    protected function getObjectByArguments()
    {
        $user = false;
        if (!is_object($user)) {
            $user = User::where('email', $this->option('user'))->first();
        }

        if (!is_object($user)) {
            $user = User::find($this->option('user'));
        }
        return $user;
    }

    /**
     * {@inheritdoc }
     */
    protected function defineInput()
    {
        return array(
            new InputArgument(
                'user',
                InputArgument::REQUIRED,
                'Use the name or email for a user to delete it.')
        );
    }
}
