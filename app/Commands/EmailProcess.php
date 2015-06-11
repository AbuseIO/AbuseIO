<?php

namespace AbuseIO\Commands;

use AbuseIO\Commands\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
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
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Queued worker is starting the processing of email file: ' . $this->filename);
    }
}
