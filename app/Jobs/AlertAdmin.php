<?php

namespace AbuseIO\Jobs;

use Config;
use Log;
use Mail;
use PHPMailer;
use PhpMimeMailParser\Parser as MimeParser;

/**
 * Class AlertAdmin.
 */
class AlertAdmin extends Job
{
    /**
     * Execute the command.
     *
     * @param string $message
     * @param array  $attachments [optional] format: ['name' => 'data']
     *
     * @return void
     */
    public static function send($message, $attachments = [])
    {
        $sent = Mail::raw(
            $message,
            function ($mail) use ($attachments) {
                $mail->from(Config::get('main.notifications.from_address'), 'AbuseIO Alerter');
                $mail->to(Config::get('main.emailparser.fallback_mail'));
                $mail->subject('Exception notification');

                foreach ($attachments as $attachmentName => $attachmentData) {
                    $mimetype = 'text/plain';
                    if (substr($attachmentName, -4) === '.eml') {
                        $mimetype = 'message/rfc822';
                    }

                    $mail->attachData(
                        $attachmentData,
                        $attachmentName,
                        [
                            'as'   => $attachmentName,
                            'mime' => $mimetype,
                        ]
                    );
                }
            }
        );

        if (!$sent) {
            Log::error(
                'AlertAdmin: '.
                'Unable to send out alert to admin '.Config::get('main.emailparser.fallback_mail')
            );
        } else {
            Log::info(
                'AlertAdmin: '.
                'Successfully send out alert to admin '.Config::get('main.emailparser.fallback_mail')
            );
        }
    }

    /**
     * Execute the command but with phpmailer to bounce it.
     *
     * @param string $message
     * @param array  $attachments [optional] format: ['name' => 'data']
     *
     * @return void
     */
    public static function bounce($rawMail)
    {

        /*
         * Parse the original e-mail into its parts needed to rebuild it into a new mail object
         */
        $parsedMail = new MimeParser();
        $parsedMail->setText($rawMail);

        /*
         * Create a new outgoing e-mail object based on direct SMTP used for bouncing it
         */
        $mail = new PHPMailer();
        $mail->isSMTP();
        if (Config::get('mail.encryption') === true) {
            $mail->SMTPSecure = 'ssl';
        }
        if (Config::get('mail.encryption') === true) {
            $mail->SMTPSecure = 'tls';
        }
        if (Config::get('mail.ssl_verify') === false) {
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ];
        }

        $mail->SMTPAuth = false;
        $mail->Host = Config::get('mail.host');
        $mail->Port = Config::get('mail.port');

        if (Config::get('mail.username') !== null &&
            Config::get('mail.password') !== null
        ) {
            $mail->SMTPAuth = true;
            $mail->Username = Config::get('mail.username');
            $mail->Password = Config::get('mail.password');
        }

        /*
         * Add all the original headers
         */
        foreach ($parsedMail->getHeaders() as $headerName => $headerValue) {
            // Skip headers that need to be set using mail methods
            if (in_array($headerName, ['to', 'from', 'subject', 'date', 'message-id', 'content-type'])) {
                continue;
            }

            if (is_array($headerValue)) {
                foreach ($headerValue as $headerSubValue) {
                    $mail->addCustomHeader($headerName, $headerSubValue);
                }
            } else {
                $mail->addCustomHeader($headerName, $headerValue);
            }
        }

        /*
         * Add all the skipped headers from addCustomHeader and add them using methods
         */

        // process 'to' header
        $rawTo = $parsedMail->getHeader('to');
        $numMatches = preg_match_all('/<(.+?)>/i', $rawTo, $matches);
        if ($numMatches !== 0) {
            $recipients = $matches[1];
            foreach ($recipients as $recipient) {
                $mail->addAddress($recipient);
            }
        } else {
            $mail->addAddress($rawTo);
        }

        // process 'from' header
        $rawFrom = $parsedMail->getHeader('from');
        $numMatches = preg_match('/<(.+?)>/i', $rawFrom, $matches);
        if ($numMatches !== 0) {
            $from = $matches[1];
        } else {
            $from = $rawFrom;
        }

        $mail->setFrom($from);
        $mail->Subject = !empty($parsedMail->getHeader('subject')) ? $parsedMail->getHeader('subject') : '';
        $mail->MessageDate = !empty($parsedMail->getHeader('date')) ? $parsedMail->getHeader('date') : date('D, j M Y H:i:s O');
        $mail->MessageID = !empty($parsedMail->getHeader('message-id')) ? $parsedMail->getHeader('message-id') : '';
        $mail->ContentType = !empty($parsedMail->getHeader('content-type')) ? $parsedMail->getHeader('content-type') : '';

        /*
         * Add required headers from bouncing accourding to RFC 2822 section 3.6.6.
         */
        $mail->addCustomHeader('Resent-From', Config::get('main.emailparser.fallback_mail'));
        $mail->addCustomHeader('Resent-To', Config::get('main.emailparser.fallback_mail'));
        $mail->addCustomHeader('Resent-Date', $mail->buildMessageDate());
        $mail->addCustomHeader('Resent-Message-Id', $mail->buildMessageID());
        $mail->addCustomHeader('Resent-User-Agent', 'AbuseIO Failed Mail Sender');

        /*
         * Add something new
         */
        $mail->XMailer = 'AbuseIO Failed Mail Sender';

        /*
         * Add the original content
         * Note: the body contains everything including mimeparts (everything except headers)
         */
        $mail->Body = preg_split('#\n\s*\n#Uis', $rawMail, 2)[1];

        // Bypass actuall recipiant(s) by changed the SMTP RCPT TO and RCPT FROM commands
        $mail->Sender = Config::get('main.notifications.from_address');
        $mail->addEnvelopeTo(Config::get('main.emailparser.fallback_mail'));

        if (!$mail->send()) {
            Log::error(
                'AlertAdmin: '.
                'Unable to bounce message to admin '.Config::get('main.emailparser.fallback_mail').
                ' error message: '.$mail->ErrorInfo
            );
        } else {
            Log::info(
                'AlertAdmin: '.
                'Successfully bounced message to admin '.Config::get('main.emailparser.fallback_mail')
            );
        }
    }
}
