<?php

namespace AbuseIO\Jobs;

use Config;
use Log;
use Mail;

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
                    $mail->attachData(
                        $attachmentData,
                        $attachmentName,
                        [
                            'as'   => $attachmentName,
                            'mime' => 'text/plain',
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
}
