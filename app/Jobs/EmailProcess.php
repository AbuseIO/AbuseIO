<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Evidence;
use AbuseIO\Parsers\Factory as ParserFactory;
use Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use PhpMimeMailParser\Parser as MimeParser;
use Storage;

/**
 * This EmailProcess class handles incoming mail messages and transform them into incidents.
 *
 * Class EmailProcess
 */
class EmailProcess extends Job implements ShouldQueue
{
    use SerializesModels;

    /**
     * Filename of the email to be processed.
     *
     * @var string
     */
    public $filename;

    /**
     * Name of the beandstalk queue to be used.
     *
     * @var string
     */
    public $queueName = 'abuseio_email_incoming';

    /**
     * Create a new EmailProcess instance.
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Queue command into named tube.
     *
     * @param object $queue
     * @param string $command
     *
     * @return void
     */
    public function queue($queue, $command)
    {
        $queue->pushOn($this->queueName, $command);
    }

    /**
     * This method is called by laravel when the job fails on a exception.
     */
    public function failed()
    {
        Log::error(
            get_class($this).': '.
            'Unexpected exception was raised from the framework. This usually indicates an error within the '.
            'framework code. A full trace can be found in the logs and should be reported to the developers'
        );

        $this->exception();
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        Log::info(
            get_class($this).': '.
            'Queued worker is starting the processing of email file: '.$this->filename
        );

        $rawEmail = Storage::get($this->filename);

        $parsedMail = new MimeParser();
        $parsedMail->setText($rawEmail);

        // pull out as variable because not all emails have a text body (some only contain html) this way they don't get ignored by the system.
        $messageBody = $parsedMail->getMessageBody() ? $parsedMail->getMessageBody('text') : $parsedMail->getMessageBody('html');
        // Sanity checks
        if (empty($parsedMail->getHeader('from')) || empty($messageBody)) {
            Log::warning(
                get_class($this).': '.
                'Missing e-mail headers from and/or empty body: '.$this->filename
            );

            $this->exception();

            return;
        }

        // Ignore email from our own notification address to prevent mail loops
        if (preg_match('/'.Config::get('main.notifications.from_address').'/', $parsedMail->getHeader('from'))) {
            Log::warning(
                get_class($this).': '.
                'Loop prevention: Ignoring email from self '.Config::get('main.notifications.from_address')
            );

            $this->exception();

            return;
        }

        // Ignore email from our own notification address used in bouncing methods to prevent mail loops
        if (preg_match('/'.Config::get('main.notifications.from_address').'/', $parsedMail->getHeader('Resent-From'))) {
            Log::warning(
                get_class($this).': '.
                'Loop prevention: Ignoring email from self '.Config::get('main.notifications.from_address')
            );

            $this->exception();

            return;
        }

        // Start with detecting valid ARF e-mail
        $attachments = $parsedMail->getAttachments();
        $arfMail = [];

        foreach ($attachments as $attachment) {
            if ($attachment->getContentType() == 'message/feedback-report') {
                $arfMail['report'] = $attachment->getContent();
            }

            if ($attachment->getContentType() == 'message/rfc822') {
                $arfMail['evidence'] = utf8_encode($attachment->getContent());
            }

            if ($attachment->getContentType() == 'text/plain') {
                $arfMail['message'] = $attachment->getContent();
            }
        }

        /*
         * Sometimes the mime header does not set the main message correctly. This is meant as a fallback and will
         * use the original content body (which is basically the same mime element). But only fallback if we actually
         * have a RFC822 message with a feedback report.
         */
        if (empty($arfMail['message']) && isset($arfMail['report']) && isset($arfMail['evidence'])) {
            $arfMail['message'] = $messageBody;
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
            Log::error(
                get_class($this).': '.
                ': No parser available to handle message from : '.$parsedMail->getHeader('from').
                ' with subject: '.$parsedMail->getHeader('subject')
            );

            $this->exception();

            return;
        }

        if ($parserResult !== false && $parserResult['errorStatus'] === true) {
            Log::error(
                get_class($parser).': '.
                ': Parser has ended with fatal errors ! : '.$parserResult['errorMessage']
            );

            $this->exception();

            return;
        } else {
            Log::info(
                get_class($parser).': '.
                ': Parser completed with '.$parserResult['warningCount'].
                ' warnings and collected '.count($parserResult['data']).' incidents to save'
            );
        }

        if ($parserResult['warningCount'] !== 0 && Config::get('main.emailparser.notify_on_warnings') === true) {
            Log::error(
                get_class($this).': '.
                'Configuration has warnings set as critical and '.
                $parserResult['warningCount'].' warnings were detected. Sending alert to administrator'
            );

            $this->exception();

            return;
        }

        /*
         * build evidence model, but wait with saving it
         * but first check if it doesnt exists already (queue retry)
         **/
        $evidenceExists = Evidence::where('filename', '=', $this->filename);
        if ($evidenceExists->count() === 1) {
            $evidence = $evidenceExists->first();
        } else {
            $evidence = new Evidence();
            $evidence->filename = $this->filename;
            $evidence->sender = $parsedMail->getHeader('from');
            $evidence->subject = $parsedMail->getHeader('subject');
        }

        /*
         * Call IncidentsProcess to validate, store evidence and save incidents
         */
        $incidentsProcess = new IncidentsProcess($parserResult['data'], $evidence);

        // Only continue if not empty, empty set is acceptable (exit OK)
        if (!$incidentsProcess->notEmpty()) {
            return;
        }

        // Validate the data set
        if (!$incidentsProcess->validate()) {
            $this->exception();

            return;
        }

        // Write the data set to database
        if (!$incidentsProcess->save()) {
            $this->exception();

            return;
        }

        Log::info(
            get_class($this).': '.
            'Queued worker has ended the processing of email file: '.$this->filename
        );
    }

    /**
     * alert administrator when problems happens. We will add the received message as attachment or bounce the original.
     *
     * @return void
     */
    protected function exception()
    {
        // we have $this->filename and $this->rawMail
        // and this Config::get('main.emailparser.fallback_mail')
        Log::error(
            get_class($this).': '.
            'Email processor ending with errors. The received e-mail '.
            'will be bounced to the admin for investigation'
        );

        $fileContents = null;
        if (Storage::exists($this->filename)) {
            $fileContents = Storage::get($this->filename);
        }

        if (Config::get('main.emailparser.use_bounce_method')) {
            AlertAdmin::bounce($fileContents);
        } else {
            AlertAdmin::send(
                'AbuseIO was not able to process an incoming message. This message is attached to this email.',
                [
                    'failed_message.eml' => $fileContents,
                ]
            );
        }
    }
}
