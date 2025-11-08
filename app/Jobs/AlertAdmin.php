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
        try {
            Mail::raw(
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

            $defaultMailer = Config::get('mail.default');
            $smtp = Config::get('mail.mailers.smtp', []);
            Log::info(
                'AlertAdmin: Successfully queued alert email',
                [
                    'to'         => Config::get('main.emailparser.fallback_mail'),
                    'mailer'     => $defaultMailer,
                    'host'       => $smtp['host'] ?? null,
                    'port'       => $smtp['port'] ?? null,
                    'encryption' => $smtp['encryption'] ?? null,
                ]
            );
        } catch (\Throwable $e) {
            Log::error(
                'AlertAdmin: Failed to send alert email',
                [
                    'to'       => Config::get('main.emailparser.fallback_mail'),
                    'error'    => $e->getMessage(),
                    'mailer'   => Config::get('mail.default'),
                ]
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
        // Read modern SMTP configuration from Laravel mailers
        $smtpConfig = Config::get('mail.mailers.smtp', []);

        // Encryption: 'ssl', 'tls', or null
        $encryption = isset($smtpConfig['encryption']) ? $smtpConfig['encryption'] : Config::get('mail.encryption');
        if ($encryption === 'ssl') {
            $mail->SMTPSecure = 'ssl';
        } elseif ($encryption === 'tls') {
            $mail->SMTPSecure = 'tls';
        } else {
            // No encryption: prevent opportunistic STARTTLS
            $mail->SMTPSecure = '';
            $mail->SMTPAutoTLS = false;
        }

        // SSL verification options (development only)
        $streamSsl = Config::get('mail.mailers.smtp.stream.ssl');
        if (is_array($streamSsl)) {
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => $streamSsl['verify_peer'] ?? true,
                    'verify_peer_name'  => $streamSsl['verify_peer_name'] ?? true,
                    'allow_self_signed' => $streamSsl['allow_self_signed'] ?? false,
                ],
            ];
        }

        // Host / Port
        $mail->Host = $smtpConfig['host'] ?? Config::get('mail.host');
        $mail->Port = $smtpConfig['port'] ?? Config::get('mail.port');

        // Authentication
        $username = $smtpConfig['username'] ?? Config::get('mail.username');
        $password = $smtpConfig['password'] ?? Config::get('mail.password');
        if (!empty($username) && !empty($password)) {
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;
        } else {
            $mail->SMTPAuth = false;
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
