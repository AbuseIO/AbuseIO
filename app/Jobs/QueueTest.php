<?php

namespace AbuseIO\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Log;

/**
 * This QueueTest class checks this queue to be actually working.
 *
 * Class QueueTest
 */
class QueueTest extends Job implements ShouldQueue
{
    use SerializesModels;

    /**
     * Name of the beandstalk queue to be used.
     *
     * @var string
     */
    public $queueName;

    /**
     * Create a new EmailProcess instance.
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
     * @param object $queue
     * @param string $command
     *
     * @return void
     */
    public function queue($queue, $command)
    {
        $queue->pushOn($this->queueName, $command);
    }

    /**
     * This method is called by laravel when the job fails on a exception.
     *
     * @param string $message
     *
     * @return void
     */
    public function failed($message)
    {
        // Start alarm bells

        Log::error(
            get_class($this).': '."Queue checked FAILED for queue {$this->queueName} {$message}"
        );
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug(
            get_class($this).': '."TestJob starting for queue {$this->queueName}"
        );

        // TODO - do stuff here

        Log::debug(
            get_class($this).': '."TestJob completed for queue {$this->queueName}"
        );
    }
}
