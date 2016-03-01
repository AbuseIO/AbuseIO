<?php

namespace AbuseIO\Console\Commands\Evidence;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Evidence;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

/**
 * Class CreateCommand
 * @package AbuseIO\Console\Commands\Evidence
 */
class CreateCommand extends AbstractCreateCommand
{
    // TODO validation of file not working
    /**
     * {@inheritdoc }
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('filename', null, 'Filename'),
                new InputArgument('sender', null, 'Sender'),
                new InputArgument('subject', null, 'Subject'),
            ]
        );
    }

    /**
     * {@inheritdoc }
     */
    public function getAsNoun()
    {
        return 'evidence';
    }

    /**
     * {@inheritdoc }
     */
    protected function getModelFromRequest()
    {
        $evidence = new Evidence();

        $evidence->filename = $this->argument('filename');
        $evidence->sender = $this->argument('sender');
        $evidence->subject = $this->argument('subject');

        return $evidence;
    }

    /**
     * {@inheritdoc }
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Evidence::createRules());
    }
}
