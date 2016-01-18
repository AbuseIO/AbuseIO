<?php

namespace AbuseIO\Console\Commands\Migrate;

use Illuminate\Console\Command;
use PhpMimeMailParser\Parser as MimeParser;
use AbuseIO\Parsers\Factory as ParserFactory;
use AbuseIO\Jobs\IncidentsValidate;
use AbuseIO\Jobs\IncidentsProcess;
use AbuseIO\Models\Evidence;
use Illuminate\Filesystem\Filesystem;
use Carbon;
use Config;
use DB;

/**
 * Class OldVersionCommand
 * @package AbuseIO\Console\Commands\Migrate
 */
class OldVersionCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'migrate:oldversion
                            {--p|prepare : Prepares the migration by building all required caches}
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'List of send out pending notifications';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {
        if (!empty($this->option('prepare'))) {
            $filesystem = new Filesystem;
            $path       = storage_path() . '/migratation/';
            umask(0007);

            if (!$filesystem->isDirectory($path)) {
                // If a datefolder does not exist, then create it or die trying
                if (!$filesystem->makeDirectory($path, 0770)) {
                    $this->error(
                        'Unable to create directory: ' . $path
                    );
                    return false;
                }

                if (!is_dir($path)) {
                    $this->error(
                        'Path vanished after write: ' . $path
                    );
                    return false;
                }
                chgrp($path, config('app.group'));
            }


            DB::setDefaultConnection('abuseio3');

            $evidences = DB::table('Evidence')
                ->get();

            foreach ($evidences as $evidence) {
                //echo $evidence->ID . PHP_EOL;
                $filename = $path . "evidence_id_{$evidence->ID}.data";

                if (is_file($filename)) {
                    continue;
                }

                $rawEmail = $evidence->Data;

                $parsedMail = new MimeParser();
                $parsedMail->setText($rawEmail);

                // Start with detecting valid ARF e-mail
                $attachments = $parsedMail->getAttachments();
                $arfMail = [];

                foreach ($attachments as $attachment) {
                    if ($attachment->contentType == 'message/feedback-report') {
                        $arfMail['report'] = $attachment->getContent();
                    }

                    if ($attachment->contentType == 'message/rfc822') {
                        $arfMail['evidence'] = utf8_encode($attachment->getContent());
                    }

                    if ($attachment->contentType == 'text/plain') {
                        $arfMail['message'] = $attachment->getContent();
                    }
                }

                if (empty($arfMail['message']) && isset($arfMail['report']) && isset($arfMail['evidence'])) {
                    $arfMail['message'] = $parsedMail->getMessageBody();
                }

                // If we do not have a complete e-mail, then we empty the perhaps partially filled arfMail
                // which is useless, hence reset to false
                if (!isset($arfMail['report']) || !isset($arfMail['evidence']) || !isset($arfMail['message'])) {
                    $arfMail = false;
                }

                // Asking ParserFactory for an object based on mappings, or die trying
                $parser = ParserFactory::create($parsedMail, $arfMail);

                if ($parser !== false) {
                    $parserResult = $parser->parse();
                } else {
                    $this->error(
                        'No parser available to handle message '.$evidence->ID.' from : ' . $evidence->Sender .
                        ' with subject: ' . $evidence->Subject
                    );
                    continue;
                }

                if ($parserResult !== false && $parserResult['errorStatus'] === true) {
                    $this->error(
                        'Parser has ended with fatal errors ! : ' . $parserResult['errorMessage']
                    );

                    var_dump($rawEmail);
                    var_dump($parserResult['data']);
                    $this->exception();
                    return;
                }

                if ($parserResult['warningCount'] !== 0 &&
                    Config::get('main.emailparser.notify_on_warnings') === true
                ) {
                    $this->error(
                        'Configuration has warnings set as critical and ' .
                        $parserResult['warningCount'] . ' warnings were detected.'
                    );

                    var_dump($rawEmail);
                    var_dump($parserResult['data']);
                    $this->exception();
                    return;
                }

                $evidenceSave = new Evidence();
                $incidentsProcess = new IncidentsProcess($parserResult['data'], $evidenceSave);

                /*
                 * Because google finds it 'obvious' not to include the IP address relating to abuse
                 * the IP field might now be empty with reparsing if the domain/label does not resolve
                 * anymore. For these cases we need to lookup the ticket that was linked to the evidence
                 * match the domain and retrieve its IP.
                 */
                foreach ($parserResult['data'] as $index => $incident) {
                    if ($incident->source == 'Google Safe Browsing' &&
                        $incident->domain != false &&
                        $incident->ip == '127.0.0.1'
                    ) {
                        // Get the list of tickets related to this evidence
                        $evidenceLinks = DB::table('EvidenceLinks')
                            ->where('EvidenceID', '=', $evidence->ID)
                            ->get();

                        // For each ticket check if the domain name is matching the evidence we need to update
                        foreach ($evidenceLinks as $evidenceLink) {
                            $ticket = DB::table('Reports')
                                ->where('ID', '=', $evidenceLink->ReportID)
                                ->first();

                            if ($ticket->Domain == $incident->domain) {
                                $incident->ip = $ticket->IP;
                            }
                        }

                        // Update the original object by overwriting it
                        $parserResult['data'][$index] = $incident;
                    }
                }


                // Only continue if not empty, empty set is acceptable (exit OK)
                if (!$incidentsProcess->notEmpty()) {
                    continue;
                }

                // Validate the data set
                if (!$incidentsProcess->validate()) {
                    $this->error(
                        'Validation failed of object.'
                    );

                    var_dump($rawEmail);
                    var_dump($parserResult['data']);
                    $this->exception();

                    return;
                }


                $incidents = [];
                foreach ($parserResult['data'] as $incident) {
                    $incidents[$incident->ip][] = $incident;
                }

                $output = [
                    'evidenceId' => $evidence->ID,
                    'evidenceData' => $evidence->Data,
                    'incidents' => $incidents,
                ];


                if ($filesystem->put($filename, json_encode($output)) === false) {
                    $this->error(
                        'Unable to write file: ' . $filename
                    );

                    return false;
                }
            }
        }

        return true;
    }

    /**
     *
     */
    private function exception()
    {
        $this->error('fatal error');
        die();
    }
}
