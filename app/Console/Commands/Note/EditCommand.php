<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Note;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class EditCommand extends AbstractEditCommand
{

    public function getOptionsList()
    {
        return new InputDefinition([
            new inputArgument('id', InputArgument::REQUIRED, 'Note id to edit'),
            new InputOption('hidden', null, InputOption::VALUE_OPTIONAL, 'Hidden'),
            new InputOption('viewed', null, InputOption::VALUE_OPTIONAL,  'Viewed'),
        ]);
    }

    public function getAsNoun()
    {
        return 'note';
    }

    protected function getModelFromRequest()
    {
        return Note::find($this->argument('id'));
    }

    protected function handleOptions($model)
    {
        $this->updateBooleanFieldWithOption($model, 'hidden');
        $this->updateBooleanFieldWithOption($model, 'viewed');

        return true;
    }

    protected function getValidator($model)
    {
        $data = $this->getModelAsArrayForDirtyAttributes($model);
        $updateRules = $this->getUpdateRulesForDirtyAttributes(Note::updateRules($model));

        return Validator::make($data, $updateRules);
    }
}
