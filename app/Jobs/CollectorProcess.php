<?php

namespace AbuseIO\Jobs;

use AbuseIO\Collectors\Factory as CollectorFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use AbuseIO\Models\Evidence;
use Log;

/**
 * This CollectorProcess class handles collections and transform them into events
 *
 * Class CollectorProcess
 */
class CollectorProcess extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    /**
     * Filename of the email to be processed
     *
     * @var string
     */
    public $collector;

    /**
     * Name of the beandstalk queue to be used
     *
     * @var string
     */
    public $queueName = 'abuseio_collector';

    /**
     * Create a new EmailProcess instance
     *
     * @param string $collector
     */
    public function __construct($collector)
    {
        $this->collector = $collector;
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
     * @return boolean
     */
    public function handle()
    {

        Log::info(
            get_class($this) . ': ' .
            'Queued worker is starting the collector: ' . $this->collector
        );

        $collector = collectorFactory::create($this->collector);

        if (!$collector) {
            Log::error(
                "The requested collector {$this->collector} could not be started check logs for PID:" . getmypid()
            );
            $this->exception();
        }

        $collectorResult = $collector->parse();

        if ($collectorResult['errorStatus'] == true) {
            Log::error(
                "The requested collector {$this->collector} returned an error. check logs for PID:" . getmypid()
            );
            $this->exception();
        }


        /**
         * save evidence onto disk
         */
        $evidence = new EvidenceSave;
        $evidenceData = json_encode(
            [
                'collectorName' => $this->collector,
                'collectorData' => $collectorResult,
            ]
        );
        $evidenceFile = $evidence->save($evidenceData);

        if (!$evidenceFile) {
            Log::error(
                get_class($this) . ': ' .
                'Error returned while asking to write evidence file, cannot continue'
            );
            $this->exception();
        }

        /**
         * build evidence model, but wait with saving it
         **/
        $evidence = new Evidence();
        $evidence->filename = $evidenceFile;
        $evidence->sender = 'abuse@localhost';
        $evidence->subject = "CLI Collector {$this->collector}";

        /**
         * Call EventsProcess to validate, store evidence and save events
         */
        $eventsProcess = new EventsProcess($collectorResult['data'], $evidence);
        $eventsProcessResult = $eventsProcess->fire();

        if (!$eventsProcessResult) {
            Log::error(
                get_class($this) . ': ' .
                'The event processor ended with errors, cannot continue'
            );

            $this->exception();
            return;
        }

        Log::info(
            get_class($this) . ': ' .
            'Queued worker has ended the processing of collector: ' . $this->collector
        );

    }

    /**
     * alert administrator when problems happens. We will add the received message as attachment or bounce the original
     *
     * @return void
     */
    protected function exception()
    {
        Log::error(
            get_class($this) . ': ' .
            'Collector processor ending with errors.'
        );

        AlertAdmin::send(
            'AbuseIO was not able to process a collection. This the logs for PID:' . getmypid()
        );
    }
}
