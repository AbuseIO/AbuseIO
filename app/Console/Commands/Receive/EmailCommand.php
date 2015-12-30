<?php

namespace AbuseIO\Console\Commands\Receive;

use AbuseIO\Jobs\EmailProcess;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;
use Uuid;
use Carbon;
use Config;

/**
 * Class EmailCommand
 * @package AbuseIO\Console\Commands\Receive
 */
class EmailCommand extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'receive:email
                            {--noQueue : Do not queue the message, but directly handle it }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses an (piped) email into abuse events.';

    /**
     * Create a new command instance.
     *
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
        Log::info(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            'Being called upon to receive an incoming e-mail'
        );

        // Read from stdin (should be piped from cat or MDA)
        $fd = fopen("php://stdin", "r");
        $rawEmail = "";

        while (!feof($fd)) {
            $rawEmail .= fread($fd, 1024);
        }

        fclose($fd);

        $filesystem = new Filesystem;
        $datefolder = Carbon::now()->format('Ymd');
        $path       = storage_path() . '/mailarchive/' . $datefolder . '/';
        $file       = Uuid::generate(4) . '.eml';
        $filename   = $path . $file;

        if (!$filesystem->isDirectory($path)) {
            // If a datefolder does not exist, then create it or die trying
            if (!$filesystem->makeDirectory($path)) {
                Log::error(
                    '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                    'Unable to create directory: ' . $path
                );
                $this->exception($rawEmail);
            }
        }

        if ($filesystem->isFile($path . $file)) {
            Log::error(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'File aready exists: ' . $filename
            );
            $this->exception($rawEmail);
        }

        if ($filesystem->put($path . $file, $rawEmail) === false) {
            Log::error(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'Unable to write file: ' . $filename
            );

            $this->exception($rawEmail);
        }

        if ($this->option('noQueue') == true) {
            // In debug mode we don't queue the job
            Log::debug(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'Queuing disabled. Directly handling message file: ' . $filename
            );

            $processer = new EmailProcess($filename);
            $processer->handle();

        } else {
            Log::info(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'Pushing incoming email into queue file: ' . $filename
            );
            $this->dispatch(new EmailProcess($filename));

        }

        Log::info(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            'Successfully received the incoming e-mail'
        );

        return true;
    }

    /**
     * We've hit a snag, so we are gracefully killing ourselves after we contact the admin about it.
     * @return mixed
     */
    protected function exception($rawEmail)
    {
        // This only bounces with config errors or problems with installations where we cannot accept
        // the email at all. In normal cases the bounce will be handled within EmailProcess::()
        Log::error(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            'Email receiver is ending with errors. The received e-mail will be bounced to the admin for investigation'
        );

        $sent = Mail::raw(
            'AbuseIO was not able to receive an incoming message. This message is attached to this email.',
            function ($message) use ($rawEmail) {
                $message->from(Config::get('main.notifications.from_address'), 'AbuseIO EmailReceiver');
                $message->to(Config::get('main.emailparser.fallback_mail'));
                $message->attachData(
                    $rawEmail,
                    'failed_message.eml',
                    [
                        'as' => 'failed_message.eml',
                        'mime' => 'message/rfc822',
                    ]
                );
            }
        );

        if (!$sent) {
            Log::error(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'Unable to send out a bounce to ' . Config::get('main.emailparser.fallback_mail')
            );
        } else {
            Log::info(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'Successfully send out a bounce to ' . Config::get('main.emailparser.fallback_mail')
            );
        }
    }
}
