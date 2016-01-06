<?php

namespace AbuseIO\Console\Commands\Receive;

use AbuseIO\Jobs\EmailProcess;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;
use Uuid;
use Carbon;
use AbuseIO\Jobs\AlertAdmin;

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
            get_class($this) . ': ' .
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
                    get_class($this) . ': ' .
                    'Unable to create directory: ' . $path
                );
                $this->exception($rawEmail);
            }
            chown($path, config('app.user'));
            chgrp($path, config('app.group'));
        }

        if ($filesystem->isFile($filename)) {
            Log::error(
                get_class($this) . ': ' .
                'File already exists: ' . $path . $filename
            );
            $this->exception($rawEmail);
            chown($path . $filename, config('app.user'));
            chgrp($path . $filename, config('app.group'));
        }

        if ($filesystem->put($filename, $rawEmail) === false) {
            Log::error(
                get_class($this) . ': ' .
                'Unable to write file: ' . $filename
            );

            $this->exception($rawEmail);
        }

        if ($this->option('noQueue') == true) {
            // In debug mode we don't queue the job
            Log::debug(
                get_class($this) . ': ' .
                'Queuing disabled. Directly handling message file: ' . $filename
            );

            $processer = new EmailProcess($filename);
            $processer->handle();

        } else {
            Log::info(
                get_class($this) . ': ' .
                'Pushing incoming email into queue file: ' . $filename
            );
            $this->dispatch(new EmailProcess($filename));

        }

        Log::info(
            get_class($this) . ': ' .
            'Successfully received the incoming e-mail'
        );

        return true;
    }

    /**
     * We've hit a snag, so we are gracefully killing ourselves after we contact the admin about it.
     *
     * @param string $rawEmail
     * @return mixed
     */
    protected function exception($rawEmail)
    {
        // This only bounces with config errors or problems with installations where we cannot accept
        // the email at all. In normal cases the bounce will be handled within EmailProcess::()
        Log::error(
            get_class($this) . ': ' .
            'Email receiver is ending with errors. The received e-mail will be bounced to the admin for investigation'
        );

        AlertAdmin::send(
            'AbuseIO was not able to receive an incoming message. This message is attached to this email.',
            [
                'failed_message.eml' => $rawEmail
            ]
        );

    }
}
