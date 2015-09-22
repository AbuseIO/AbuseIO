<?php

namespace AbuseIO\Console\Commands;

use AbuseIO\Commands\EmailProcess;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputOption;
use Log;
use Events;
use Uuid;
use Carbon;
use Config;

class EmailReceiveCommand extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     * @var string
     */
    protected $name = 'email:receive';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Parses an incoming email into abuse events.';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        Log::info(get_class($this).': Being called upon to receive an incoming e-mail');

        if ($this->option('debug') === true) {
            Log::debug(get_class($this).': Debug mode has been enabled');
        }

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
                Log::error(get_class($this).': Unable to create directory: ' . $path);
                $this->exception($rawEmail);
            }
        }

        if ($filesystem->isFile($path . $file)) {
            Log::error(get_class($this).': File aready exists: ' . $filename);
            $this->exception($rawEmail);
        }

        if ($filesystem->put($path . $file, $rawEmail) === false) {
            Log::error(get_class($this).': Unable to write file: ' . $filename);

            $this->exception($rawEmail);
        }

        if ($this->option('debug') == true) {
            // In debug mode we don't queue the job
            Log::debug(get_class($this).': Directly handling message file: ' . $filename);

            $processer = new EmailProcess($filename);
            $processer->handle();

        } else {
            Log::info(get_class($this).': Pushing incoming email into queue file: ' . $filename);
            $this->dispatch(new EmailProcess($filename));

        }

        Log::info(get_class($this).': Successfully received the incoming e-mail');
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [ ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        // TODO: logging to console instead in addition to logfile
        return [
            [
                'debug',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Enable debugging while pushing e-mail from CLI.',
                false
            ],
        ];
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
            get_class($this).': Email receiver is ending with errors. "
            ."The received e-mail will be bounced to the admin for investigation'
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
                get_class($this).': Unable to send out a bounce to ' . Config::get('main.emailparser.fallback_mail')
            );
        } else {
            Log::info(
                get_class($this).': Successfully send out a bounce to ' . Config::get('main.emailparser.fallback_mail')
            );
        }
    }
}
