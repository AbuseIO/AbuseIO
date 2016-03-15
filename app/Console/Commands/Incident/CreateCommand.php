<?php

namespace AbuseIO\Console\Commands\Incident;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Incident;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;
use AbuseIO\Jobs\EvidenceSave;

/**
 * Class CreateCommand
 * @package AbuseIO\Console\Commands\Account
 */
class CreateCommand extends AbstractCreateCommand
{
    // TODO: Somehow call evidenceProcess(with incident wrapped in array, with evidence build)
    // TODO: Somehow check if the evidence is used (incident did not fail) or remote it if not used
    // TODO: Idea is to make a custom handle() however thats currently not possible due to final functions in abstract

    /**
     * @return InputDefinition
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('source', InputArgument::REQUIRED, 'Name of the source'),
                new InputArgument("ip", InputArgument::REQUIRED, "ip address"),
                new InputArgument("domain", InputArgument::REQUIRED, "domain name"),
                new InputArgument('uri', InputArgument::REQUIRED, 'uri or path'),
                new InputArgument("class", InputArgument::REQUIRED, "a preconfigured abuse classification"),
                new InputArgument("type", InputArgument::REQUIRED, "a preconfigured abuse type"),
                new InputArgument("timestamp", InputArgument::REQUIRED, "UNIX timestamp"),
                new InputArgument("information", InputArgument::REQUIRED, "information data in single string or JSON"),
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

        $incident->source = $this->argument('source');
        $incident->ip = $this->argument('ip');
        $incident->domain = $this->argument('domain');
        $incident->uri = $this->argument('uri');
        $incident->class = $this->argument('class');
        $incident->type = $this->argument('type');
        $incident->timestamp = $this->argument('timestamp');

        if (is_object(json_decode($this->argument('information')))) {
            // JSON object given which we can directly use in the incident
            $incident->information = $this->argument('information');

        } else {
            // String given so wrapping it into a data json object
            $incident->information = json_encode(
                [
                    'data' => $this->argument('information'),
                ]
            );
        }

        /*
         * Save the evidence as its required to save incidents
         */
        $evidence = new EvidenceSave;
        $evidenceData = [
            'CreatedBy'     => trim(posix_getpwuid(posix_geteuid())['name']) . ' (CLI)',
            'receivedOn'    => time(),
            'submittedData' => $incident->toArray(),
            'attachments'   => [],
        ];

        // Add the file to evidence object if it was given
        if ($this->argument('file') !== null) {
            // Build evidence with added file
            if (!is_file($this->argument('file'))) {
                $this->error('File does not exist: ' . $this->argument('file'));
                die();
            }

            $attachment = [
                'filename' => $this->argument('file'),
                'size' => filesize($this->argument('file')),
                'contentType' => mime_content_type($this->argument('file')),
                'data' => file_get_contents($this->argument('file'))
            ];
        }

        $evidenceFile = $evidence->save(json_encode($evidenceData));

        if (!$evidenceFile) {
            $this->error('Error returned while asking to write evidence file, cannot continue');
            die();
        }

        return $incident;
    }

    /**
     * {@inheritdoc }
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Incident::createRules());
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    /*
    final public function handle()
    {
        $model = $this->getModelFromRequest();
    }
    */
}
