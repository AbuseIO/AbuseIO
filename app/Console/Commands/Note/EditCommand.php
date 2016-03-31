<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Note;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Validator;

/**
 * Class EditCommand.
 */
class EditCommand extends AbstractEditCommand
{
    /**
     * {@inheritdoc}.
     */
    public function getOptionsList()
    {
        return new InputDefinition(
            [
                new inputArgument('id', InputArgument::REQUIRED, 'Note id to edit'),
                new InputOption('hidden', null, InputOption::VALUE_OPTIONAL, 'Hidden'),
                new InputOption('viewed', null, InputOption::VALUE_OPTIONAL, 'Viewed'),
            ]
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getAsNoun()
    {
        return 'note';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getModelFromRequest()
    {
        return Note::find($this->argument('id'));
    }

    /**
     * {@inheritdoc}.
     */
    protected function handleOptions($model)
    {
        $this->updateBooleanFieldWithOption($model, 'hidden');
        $this->updateBooleanFieldWithOption($model, 'viewed');

        return true;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        $data = $this->getModelAsArrayForDirtyAttributes($model);
        $updateRules = $this->getUpdateRulesForDirtyAttributes(Note::updateRules());

        return Validator::make($data, $updateRules);
    }
}
