<?php

namespace AbuseIO\Console\Commands\Migrate;

use Illuminate\Console\Command;
use PhpMimeMailParser\Parser as MimeParser;
use AbuseIO\Parsers\Factory as ParserFactory;
use AbuseIO\Jobs\EvidenceSave;
use AbuseIO\Jobs\IncidentsValidate;
use AbuseIO\Jobs\IncidentsProcess;
use AbuseIO\Jobs\FindContact;
use AbuseIO\Models\Evidence;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use Illuminate\Filesystem\Filesystem;
use Validator;
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
                            {--s|start : Start the migration using cached evidence }
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
        // todo - dont forget the notes!
        if (!empty($this->option('prepare'))) {
            $this->info('building required evidence cache files');

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
                    // Before we go into an error, lets see if this evidence was even linked to any ticket at all
                    // If not we can ignore the error and just smile and wave
                    $evidenceLinks = DB::table('EvidenceLinks')
                        ->where('EvidenceID', '=', $evidence->ID)
                        ->get();

                    if (count($evidenceLinks) === 0) {
                        continue;
                    }

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

                    $this->exception();
                }

                if ($parserResult['warningCount'] !== 0 &&
                    Config::get('main.emailparser.notify_on_warnings') === true
                ) {
                    $this->error(
                        'Configuration has warnings set as critical and ' .
                        $parserResult['warningCount'] . ' warnings were detected.'
                    );

                    $this->exception();
                }

                // Write the evidence into the archive
                $evidenceWrite = new EvidenceSave;
                $evidenceData = $rawEmail;
                $evidenceFile = $evidenceWrite->save($evidenceData);

                // Save the file reference into the database
                $evidenceSave = new Evidence();
                $evidenceSave->filename = $evidenceFile;
                $evidenceSave->sender = $parsedMail->getHeader('from');
                $evidenceSave->subject = $parsedMail->getHeader('subject');

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
                    $this->exception();
                }


                $incidents = [];
                foreach ($parserResult['data'] as $incident) {
                    $incidents[$incident->ip][] = $incident;
                }

                DB::setDefaultConnection('mysql');
                $evidenceSave->save();
                DB::setDefaultConnection('abuseio3');

                $output = [
                    'evidenceId' => $evidence->ID,
                    'evidenceData' => $evidence->Data,
                    'incidents' => $incidents,
                    'newId' => $evidenceSave->id,
                ];

                if ($filesystem->put($filename, json_encode($output)) === false) {
                    $this->error(
                        'Unable to write file: ' . $filename
                    );

                    return false;
                }
            }
        }



        if (!empty($this->option('start'))) {
            /*
            $this->info('starting migration - phase 1 - contact data');

            DB::setDefaultConnection('abuseio3');

            $customers = DB::table('Customers')
                ->get();

            DB::setDefaultConnection('mysql');

            foreach ($customers as $customer) {
                $newContact = new Contact();
                $newContact->reference = $customer->Code;
                $newContact->name = $customer->Name;
                $newContact->email = $customer->Contact;
                $newContact->auto_notify = $customer->AutoNotify;
                $newContact->enabled = 1;
                $newContact->account_id = 1;

                $validation = Validator::make($newContact->toArray(), Contact::createRules());

                if ($validation->fails()) {
                    $message = implode(' ', $validation->messages()->all());
                    $this->error('fatal error while creating contacts :' . $message);
                    $this->exception();
                } else {
                    $newContact->save();
                }
            }
            */

            /*
            $this->info('starting migration - phase 2 - netblock data');

            DB::setDefaultConnection('abuseio3');

            $netblocks = DB::table('Netblocks')
                ->get();

            DB::setDefaultConnection('mysql');

            foreach ($netblocks as $netblock) {
                $contact = FindContact::byId($netblock->CustomerCode);

                if ($contact->reference != $netblock->CustomerCode) {
                    $this->error('Contact lookup failed, mismatched results');
                    $this->$this->exception();
                }

                $newNetblock = new Netblock();
                $newNetblock->first_ip = long2ip($netblock->begin_in);
                $newNetblock->last_ip = long2ip($netblock->end_in);
                $newNetblock->description = 'Imported from previous AbuseIO version which did not include a description';
                $newNetblock->contact_id = $contact->id;
                $newNetblock->enabled = 1;

                $validation = Validator::make($newNetblock->toArray(), Netblock::createRules($newNetblock));

                if ($validation->fails()) {
                    $message = implode(' ', $validation->messages()->all());
                    $this->error('fatal error while creating contacts :' . $message);
                    $this->exception();
                } else {
                    $newNetblock->save();
                }
            }
            */

            $this->info('starting migration - phase 3 - ticket and evidence data');

            DB::setDefaultConnection('abuseio3');

            $tickets = DB::table('Reports')
                ->get();

            DB::setDefaultConnection('mysql');

            foreach ($tickets as $ticket) {
                // Get the list of evidence ID's related to this ticket
                DB::setDefaultConnection('abuseio3');
                $evidenceLinks = DB::table('EvidenceLinks')
                    ->where('ReportID', '=', $ticket->ID)
                    ->get();

                DB::setDefaultConnection('mysql');

                // DO NOT REMOVE! Legacy versions (1.0 / 2.0) have imports without evidence.
                // These dont have any linked evidence and will require a manual building of evidence
                // for now we ignore them. This will not affect any 3.x installations
                if ($ticket->CustomerName == 'Imported from AbuseReporter' ||
                    !empty(json_decode($ticket->Information)->importnote)
                ) {
                    continue;
                }


                if (count($evidenceLinks) != (int)$ticket->ReportCount) {
                    // Count does not match, known 3.0 bug so we will do a little magic to fix that
                } else {
                    foreach ($evidenceLinks as $evidenceLink) {

                    }
                    var_dump($ticket);
                    var_dump($evidenceLinks);
                    die();
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
        $this->error('fatal error happend, ending migration (empty DB, fix problem, try again)');
        die();
    }
}
