<?php

namespace AbuseIO\Console\Commands\Incident;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Incident;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

/**
 * Class CreateCommand
 * @package AbuseIO\Console\Commands\Account
 */
class CreateCommand extends AbstractCreateCommand
{
    /**
     * @return InputDefinition
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('source', InputArgument::REQUIRED, 'source'),
                new InputArgument("ip", InputArgument::REQUIRED, "ip"),
                new InputArgument("domain", InputArgument::REQUIRED, "domain"),
                new InputArgument('uri', InputArgument::REQUIRED, 'uri'),
                new InputArgument("class", InputArgument::REQUIRED, "class"),
                new InputArgument("type", InputArgument::REQUIRED, "type"),
                new InputArgument("timestamp", InputArgument::REQUIRED, "timestamp"),
                new InputArgument("information", InputArgument::REQUIRED, "information"),
                new InputArgument('file', InputArgument::OPTIONAL, 'Optionally add a file as evidence'),
            ]
        );
    }

    /**
     * {@inheritdoc }
     */
    public function getAsNoun()
    {
        return "incident";
    }

    /**
     * {@inheritdoc }
     */
    protected function getModelFromRequest()
    {
        $incident = new Incident();

        /*
         * Source can be freely chosen
         */
        $incident->source = $this->argument('source');

        /*
         * Domain can be set to - to set this value to false
         */
        if ($this->argument('domain') != '-') {
            // If set this can be a domain or a URL
            $incident->domain = $this->argument('domain');

            // URI can be set to - to set this value to false
            $incident->uri = $this->argument('uri');
        } else {
            $incident->domain = false;
            $incident->uri = false;
        }

        /*
         * Ip can be set to - to set use resolving, only works if domain is set
         */
        if ($this->argument('ip') == '-' &&
            $this->argument('domain') != '-'
        ) {

        } else {
            $incident->ip = $this->argument('ip');
        }

        /*
         * Must be existing classes, perhaps a incident:classes & types as helpers?
         */
        $incident->class = $this->argument('class');
        $incident->type = $this->argument('type');

        /*
         * Timestamp can be a unix timestamp or a date formated field
         */
        $incident->timestamp = $this->argument('timestamp');

        /*
         * This is going to be a challenge to build :/
         */
        $incident->information = $this->argument('information');

        /*
         * Optional field once set it requires a valid is_file() and its added onto the information blob
         * by building a Evidence tag. Else the Evidence tag would just be the information field.
         */
        if ($this->argument('file') === 'NULL') {
            // Build evidence without file
        } else {
            // Build evidence with file
        }

        // Somehow call evidenceProcess(with incident wrapped in array, with evidence build)
        // Somehow check if the evidence is used (incident did not fail) or remote it if not used
        return $incident;
    }

    /**
     * {@inheritdoc }
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Incident::createRules());
    }
}
