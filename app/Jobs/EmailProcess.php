<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Evidence;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\Filesystem;
use PhpMimeMailParser\Parser as MimeParser;
use AbuseIO\Parsers\Factory as ParserFactory;
use Config;
use Log;
use AbuseIO\Jobs\AlertAdmin;

/**
 * This EmailProcess class handles incoming mail messages and transform them into events
 *
 * Class EmailProcess
 */
class EmailProcess extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    /**
     * Filename of the email to be processed
     *
     * @var string
     */
    public $filename;

    /**
     * Name of the beandstalk queue to be used
     *
     * @var string
     */
    public $queueName = 'abuseio_email_incoming';

    /**
     * Create a new EmailProcess instance
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
     * @param  object $queue
     * @param  string $command
     * @return void
     */
    public function queue($queue, $command)
    {
        $queue->pushOn($this->queueName, $command);
    }

    /**
     * Execute the command
     *
     * @return void
     */
    public function handle()
    {

        Log::info(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            'Queued worker is starting the processing of email file: ' . $this->filename
        );

        $filesystem = new Filesystem;
        $rawEmail = $filesystem->get($this->filename);

        $parsedMail = new MimeParser();
        $parsedMail->setText($rawEmail);

        // Sanity checks
        if (empty($parsedMail->getHeader('from')) || empty($parsedMail->getMessageBody())) {
            Log::warning(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'Missing e-mail headers from and/or empty body: ' . $this->filename
            );

            $this->alertAdmin();
            return;
        }

        // Ignore email from our own notification address to prevent mail loops
        if (preg_match('/' . Config::get('main.notifications.from_address') . '/', $parsedMail->getHeader('from'))) {
            Log::warning(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'Loop prevention: Ignoring email from self ' . Config::get('main.notifications.from_address')
            );

            $this->alertAdmin();
            return;
        }

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

        /*
         * Sometimes the mime header does not set the main message correctly. This is ment as a fallback and will
         * use the original content body (which is basicly the same mime element). But only fallback if we actually
         * have a RFC822 message with a feedback report.
         */
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
            Log::error(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                ': No parser available to handle message from : ' . $parsedMail->getHeader('from') .
                ' with subject: ' . $parsedMail->getHeader('subject')
            );

            $this->alertAdmin();
            return;
        }

        if ($parserResult !== false && $parserResult['errorStatus'] === true) {
            Log::error(
                '(JOB ' . getmypid() . ') ' . get_class($parser) . ': ' .
                ': Parser has ended with fatal errors ! : ' . $parserResult['errorMessage']
            );

            $this->alertAdmin();
            return;
        } else {
            Log::info(
                '(JOB ' . getmypid() . ') ' . get_class($parser) . ': ' .
                ': Parser completed with ' . $parserResult['warningCount'] .
                ' warnings and collected ' . count($parserResult['data']) . ' events to save'
            );
        }

        if ($parserResult['warningCount'] !== 0 && Config::get('main.emailparser.notify_on_warnings') === true) {
            Log::error(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'Configuration has warnings set as critical and ' .
                $parserResult['warningCount'] . ' warnings were detected. Sending alert to administrator'
            );

            $this->alertAdmin();
            return;
        }

        if (count($parserResult['data']) !== 0) {
            // Call validator
            $validator = new EventsValidate();
            $validatorResult = $validator->check($parserResult['data']);

            if ($validatorResult['errorStatus'] === true) {
                Log::error(
                    '(JOB ' . getmypid() . ') ' . get_class($validator) . ': ' .
                    'Validator has ended with errors ! : ' . $validatorResult['errorMessage']
                );

                $this->alertAdmin();
                return;
            } else {
                Log::info(
                    '(JOB ' . getmypid() . ') ' . get_class($validator) . ': ' .
                    'Validator has ended without errors'
                );
            }

            /**
             * save evidence into table
             **/
            $evidence = new Evidence();
            $evidence->filename = $this->filename;
            $evidence->sender = $parsedMail->getHeader('from');
            $evidence->subject = $parsedMail->getHeader('subject');
            $evidence->save();

            /**
             * call saver
             **/
            $saver = new EventsSave();
            $saverResult = $saver->save($parserResult['data'], $evidence->id);

            /**
             * We've hit a snag, so we are gracefully killing ourselves
             * after we contact the admin about it. EventsSave should never
             * end with problems unless the mysql died while doing transactions
             **/
            if ($saverResult['errorStatus'] === true) {
                Log::error(
                    '(JOB ' . getmypid() . ') ' . get_class($saver) . ': ' .
                    'Saver has ended with errors ! : ' . $saverResult['errorMessage']
                );

                $this->alertAdmin();
                return;
            } else {
                Log::info(
                    '(JOB ' . getmypid() . ') ' . get_class($saver) . ': ' .
                    'Saver has ended without errors'
                );
            }
        } else {
            Log::warning(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'Parser did not return any events therefore skipping validation and saving a empty event set'
            );
        }

        Log::info(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            'Queued worker has ended the processing of email file: ' . $this->filename
        );
    }

    /**
     * alert administrator when problems happens. We will add the received message as attachment or bounce the original
     *
     * @return void
     */
    protected function alertAdmin()
    {
        // we have $this->filename and $this->rawMail
        // and this Config::get('main.emailparser.fallback_mail')
        Log::error(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            'Email processor ending with errors. The received e-mail will be deleted from ' .
            'archive and bounced to the admin for investigation'
        );

        AlertAdmin::send(
            'AbuseIO was not able to process an incoming message. This message is attached to this email.',
            [
                'failed_message.eml' => @file_get_contents($this->filename)
            ]
        );

        // Delete the evidence file as we are not using it.
        $filesystem = new Filesystem;
        $filesystem->delete($this->filename);

    }
}
