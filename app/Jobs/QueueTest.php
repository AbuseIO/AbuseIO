<?php

namespace AbuseIO\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

/**
 * This QueueTest class checks this queue to be actually working
 *
 * Class QueueTest
 */
class QueueTest extends Job implements SelfHandling, ShouldQueue
{
    /**
     * Name of the beandstalk queue to be used
     *
     * @var string
     */
    public $queueName;

    /**
     * Create a new EmailProcess instance
     *
     * @param string $queueName
     */
    public function __construct($queueName)
    {
        $this->queueName = $queueName;
    }

    /**
     * Queue command into named tube.
     *
     * @param  object $queue
     * @param  string $command
     * @return void
     */
    public function queue($queue, $command)
    {
        $queue->pushOn($this->queueName, $command);
    }

    /**
     * Execute the command
     *
     * @return void
     */
    public function handle()
    {
        Log::info(
            get_class($this) . ': ' . "TestJob starting for queue {$this->queueName}"
        );

        // TODO - do stuff here

        Log::info(
            get_class($this) . ': ' . "TestJob completed for queue {$this->queueName}"
        );
    }

    /**
     * Log when failed
     *
     * @param string $message
     * @return void
     */
    public function failed($message)
    {
        // Start alarm bells

        Log::error(
            get_class($this) . ': ' . "Queue checked FAILED for queue {$this->queueName}"
        );
    }
}
