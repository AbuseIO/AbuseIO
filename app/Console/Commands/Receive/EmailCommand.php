<?php

namespace AbuseIO\Console\Commands\Receive;

use AbuseIO\Jobs\AlertAdmin;
use AbuseIO\Jobs\EmailProcess;
use AbuseIO\Jobs\EvidenceSave;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;

/**
 * Class EmailCommand.
 */
class EmailCommand extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'email:receive
                            {--noqueue : Do not queue the message, but directly handle it }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses an (piped) email into abuse events.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        Log::info(
            get_class($this).': '.
            'Being called upon to receive an incoming e-mail'
        );

        // Read from stdin (should be piped from cat or MDA)
        $fd = fopen('php://stdin', 'r');
        $rawEmail = '';

        while (!feof($fd)) {
            $rawEmail .= fread($fd, 1024);
        }

        fclose($fd);

        /*
         * save evidence onto disk
         */
        $evidence = new EvidenceSave();
        $evidenceData = $rawEmail;
        $evidenceFile = $evidence->save($evidenceData);

        if (!$evidenceFile) {
            Log::error(
                get_class($this).': '.
                'Error returned while asking to write evidence file, cannot continue'
            );
            $this->exception($rawEmail);
        }

        if ($this->option('noqueue') == true) {
            // In debug mode we don't queue the job
            Log::debug(
                get_class($this).': '.
                'Queuing disabled. Directly handling message file: '.$evidenceFile
            );

            $processer = new EmailProcess($evidenceFile);
            $processer->handle();
        } else {
            Log::info(
                get_class($this).': '.
                'Pushing incoming email into queue file: '.$evidenceFile
            );
            $this->dispatch(new EmailProcess($evidenceFile));
        }

        Log::info(
            get_class($this).': '.
            'Successfully received the incoming e-mail'
        );

        return true;
    }

    /**
     * We've hit a snag, so we are gracefully killing ourselves after we contact the admin about it.
     *
     * @param string $rawEmail
     *
     * @return mixed
     */
    protected function exception($rawEmail)
    {
        // This only bounces with config errors or problems with installations where we cannot accept
        // the email at all. In normal cases the bounce will be handled within EmailProcess::()
        Log::error(
            get_class($this).': '.
            'Email receiver is ending with errors. The received e-mail will be bounced to the admin for investigation'
        );

        AlertAdmin::send(
            'AbuseIO was not able to receive an incoming message. This message is attached to this email.',
            [
                'failed_message.eml' => $rawEmail,
            ]
        );
    }
}
