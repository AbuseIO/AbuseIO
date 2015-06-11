<?php

namespace AbuseIO\Commands;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use PhpMimeMailParser\Parser;
use Config;
use Log;

class EmailProcess extends Command implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $filename;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Queue command into named tube.
     *
     * @return void
     */
    public function queue($queue, $command)
    {
        $queue->pushOn('emails', $command);
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Queued worker is starting the processing of email file: ' . $this->filename);

        $rawEmail = file_get_contents($this->filename);

        $parsedMail = new Parser();
        $parsedMail->setText($rawEmail);

        // Ignore email from our own notification address to prevent mail loops
        // TODO

        // Start with detecting valid ARF e-mail
        $attachments = $parsedMail->getAttachments();
        $arfEmail = [ ];
        foreach ($attachments as $attachment) {
            if ($attachment->contentType == 'message/feedback-report') {
                $arfEmail['report'] = $attachment->getContent();
            }
            if ($attachment->contentType == 'message/rfc822') {
                $arfEmail['evidence'] = $attachment->getContent();
            }
            if ($attachment->contentType == 'text/plain') {
                $arfEmail['message'] = $attachment->getContent();
            }
        }

        // Use parser mapping to see where to kick it to
        // Start using the quick method using the sender mapping
        $senderMap = Config::get('main.emailparser.sender_map');
        foreach ($senderMap as $regex => $mapping) {
            if (preg_match($regex, $parsedMail->getHeader('from'))) {
                $parser = $mapping;
                break;
            }
        }

        // If the quick method didnt work fall back to body mapping
        if (empty($parsedMail)) {
            $bodyMap = Config::get('main.emailparser.body_map');
            foreach ($bodyMap as $regex => $mapping) {
                if (preg_match($regex, $parsedMail->getMessageBody())) {
                    $parser = $mapping;
                    break;
                }
            }
        }

        // If we haven't figured out which parser we're going to use, we will never find out so another rage quit
        if (empty($parser)) {
            Log::error('Unable to handle message from: ' . $parsedMail->getHeader('from') . ' with subject: ' . $parsedMail->getHeader('subject'));
            $this->exception($rawEmail);
        } else {
            Log::info('Received message from: '. $parsedMail->getHeader('from') . ' with subject: ' . $parsedMail->getHeader('subject') . ' heading to parser: ' . $parser);
        }



    }

}
