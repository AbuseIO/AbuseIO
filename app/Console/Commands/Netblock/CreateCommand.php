<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Netblock;
use AbuseIO\Models\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

/**
 * Class CreateCommand.
 */
class CreateCommand extends AbstractCreateCommand
{
    // TODO validation of file not working

    /**
     * {@inheritdoc}.
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('contact', null, 'Id from contact'),
                new InputArgument('first_ip', null, 'Start Ip address from netblock'),
                new InputArgument('last_ip', null, 'Last Ip address from netblock'),
                new InputArgument('description', null, 'Description'),
                new InputArgument('enabled', null, 'Set the account to be enabled', false),
            ]
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getAsNoun()
    {
        return 'netblock';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getModelFromRequest()
    {
        $netblock = new Netblock();

        $netblock->contact()->associate(
            User::find($this->argument('contact'))
        );
        $netblock->first_ip = $this->argument('first_ip');
        $netblock->last_ip = $this->argument('last_ip');
        $netblock->description = $this->argument('description');
        $netblock->enabled = $this->argument('enabled') === 'true' ? true : false;

        return $netblock;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Netblock::createRules($model));
    }
}
