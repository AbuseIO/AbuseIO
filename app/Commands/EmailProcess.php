<?php

namespace AbuseIO\Commands;

use AbuseIO\Models\Evidence;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\Filesystem;
use PhpMimeMailParser\Parser as MimeParser;
use AbuseIO\Parsers\Factory as ParserFactory;
use AbuseIO\Commands\EventsValidate;
use AbuseIO\Commands\EventsSave;
use Config;
use Log;
use Mail;

class EmailProcess extends Command implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Filename of the email to be processed
     * @var string
     */
    public $filename;

    /**
     * Create a new EmailProcess instance
     * @param string $filename
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Queue command into named tube.
     * @param  object $queue
     * @param  string $command
     * @return void
     */
    public function queue($queue, $command)
    {
        $queue->pushOn('emails', $command);
    }

    /**
     * Execute the command
     * @return void
     */
    public function handle()
    {
        Log::info(get_class($this) . ': Queued worker is starting the processing of email file: ' . $this->filename);

        $filesystem = new Filesystem;
        $rawEmail = $filesystem->get($this->filename);

        $parsedMail = new MimeParser();
        $parsedMail->setText($rawEmail);

        // Sanity checks
        if (empty($parsedMail->getHeader('from')) || empty($parsedMail->getMessageBody())) {
            Log::warning(get_class($this) . ' Missing e-mail headers from and/or empty body: ' . $this->filename);

            $this->alertAdmin();
            return;
        }

        // Ignore email from our own notification address to prevent mail loops
        if (preg_match('/' . Config::get('main.notifications.from_address') . '/', $parsedMail->getHeader('from'))) {
            Log::warning(
                get_class($this) . 'Loop prevention: Ignoring email from self '
                . Config::get('main.notifications.from_address')
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
                $arfMail['evidence'] = $attachment->getContent();
            }

            if ($attachment->contentType == 'text/plain') {
                $arfMail['message'] = $attachment->getContent();
            }
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
                get_class($this)
                . ': No parser available to handle message from : ' . $parsedMail->getHeader('from')
                . ' with subject: ' . $parsedMail->getHeader('subject')
            );

            $this->alertAdmin();
            return;
        }

        if ($parserResult !== false && $parserResult['errorStatus'] === true) {
            Log::error(
                get_class($parser) . ': Parser has ended with fatal errors ! : ' . $parserResult['errorMessage']
            );

            $this->alertAdmin();
            return;
        } else {
            Log::info(
                get_class($parser)
                . ': Parser completed with ' . $parserResult['warningCount'] .
                ' warnings and collected ' . count($parserResult['data']) . ' events to save'
            );
        }

        if ($parserResult['warningCount'] !== 0 && Config::get('main.emailparser.notify_on_warnings') === true) {
            Log::error(
                get_class($parser) . ': Configuration has warnings set as critical and ' .
                $parserResult['warningCount'] . ' warnings were detected. Sending alert to administrator'
            );

            $this->alertAdmin();
            return;
        }

        if (count($parserResult['data']) !== 0) {
            // Call validator
            $validator = new EventsValidate($parserResult['data']);
            $validatorResult = $validator->handle();

            if ($validatorResult['errorStatus'] === true) {
                Log::error(
                    get_class($validator).': Validator has ended with errors ! : ' . $validatorResult['errorMessage']
                );

                $this->alertAdmin();
                return;
            } else {
                Log::info(get_class($validator).': Validator has ended without errors');
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
            $saver = new EventsSave($parserResult['data'], $evidence->id);
            $saverResult = $saver->handle();

            /**
             * We've hit a snag, so we are gracefully killing ourselves
             * after we contact the admin about it. EventsSave should never
             * end with problems unless the mysql died while doing transactions
             **/
            if ($saverResult['errorStatus'] === true) {
                Log::error(
                    get_class($saver) . ': Saver has ended with errors ! : ' . $saverResult['errorMessage']
                );

                $this->alertAdmin();
                return;
            } else {
                Log::info(get_class($saver) . ': Saver has ended without errors');
            }
        } else {
            Log::warning(
                get_class($this) .
                ': Parser did not return any events therefore skipping validation and saving a empty event set'
            );
        }

        Log::info(get_class($this).': Queued worker has ended the processing of email file: ' . $this->filename);
    }

    /**
     * alert administrator when problems happens. We will add the received message as attachment or bounce the original
     * @return Boolean
     */
    protected function alertAdmin()
    {
        // we have $this->filename and $this->rawMail
        // and this Config::get('main.emailparser.fallback_mail')
        Log::error(
            get_class($this).': Email processor ending with errors. The received e-mail will be deleted from '
            . 'archive and bounced to the admin for investigation'
        );

        $filename = $this->filename;

        // Send a e-mail to the admin about the failed parse attempt
        $sent = Mail::raw(
            'AbuseIO was not able to handle an incoming message. This message is attached to this email.',
            function ($message) use ($filename) {
                $message->from(Config::get('main.notifications.from_address'), 'AbuseIO EmailProcess');
                $message->to(Config::get('main.emailparser.fallback_mail'));
                $message->attach(
                    $filename,
                    [
                        'as' => 'failed_message.eml',
                        'mime' => 'message/rfc822',
                    ]
                );
            }
        );

        if (!$sent) {
            Log::error(
                get_class($this).': Unable to send out a bounce to ' . Config::get('main.emailparser.fallback_mail')
            );
        } else {
            Log::info(
                get_class($this).': Successfully send out a bounce to ' . Config::get('main.emailparser.fallback_mail')
            );
        }

        // Delete the evidence file as we are not using it.
        $filesystem = new Filesystem;
        $filesystem->delete($filename);

    }
}
