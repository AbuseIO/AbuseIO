<?php

namespace AbuseIO\Jobs;

use AbuseIO\Collectors\Factory as CollectorFactory;
use AbuseIO\Models\Evidence;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Log;

/**
 * This CollectorProcess class handles collections and transform them into incidents.
 *
 * Class CollectorProcess
 */
class CollectorProcess extends Job implements ShouldQueue
{
    use SerializesModels;

    /**
     * Filename of the email to be processed.
     *
     * @var string
     */
    public $collector;

    /**
     * Name of the beandstalk queue to be used.
     *
     * @var string
     */
    public $queueName = 'abuseio_collector';

    /**
     * Create a new EmailProcess instance.
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
     */
    public function failed()
    {
        Log::error(
            get_class($this).': '.
            'Unexpected exception was raised from the framework. This usually indicates an error within the '.
            'framework code. A full trace can be found in the logs and should be reported to the developers'
        );

        $this->exception();
    }

    /**
     * Execute the command.
     *
     * @return bool
     */
    public function handle()
    {
        Log::info(
            get_class($this).': '.
            'Queued worker is starting the collector: '.$this->collector
        );

        $collector = collectorFactory::create($this->collector);

        if (!$collector) {
            Log::error(
                "The requested collector {$this->collector} could not be started check logs for PID:".getmypid()
            );
            $this->exception();

            return;
        }

        $collectorResult = $collector->parse();

        if ($collectorResult['errorStatus'] == true) {
            Log::error(
                "The requested collector {$this->collector} returned an error. check logs for PID:".getmypid()
            );
            $this->exception();

            return;
        }

        /*
         * save evidence onto disk
         */
        $evidence = new EvidenceSave();
        $evidenceData = json_encode(
            [
                'collectorName' => $this->collector,
                'collectorData' => $collectorResult,
            ]
        );
        $evidenceFile = $evidence->save($evidenceData);

        if (!$evidenceFile) {
            Log::error(
                get_class($this).': '.
                'Error returned while asking to write evidence file, cannot continue'
            );
            $this->exception();

            return;
        }

        /*
         * build evidence model, but wait with saving it
         **/
        $evidence = new Evidence();
        $evidence->filename = $evidenceFile;
        $evidence->sender = 'abuse@localhost';
        $evidence->subject = "CLI Collector {$this->collector}";

        /*
         * Call IncidentsProcess to validate, store evidence and save incidents
         */
        $incidentsProcess = new IncidentsProcess($collectorResult['data'], $evidence);

        // Only continue if not empty, empty set is acceptable (exit OK)
        if (!$incidentsProcess->notEmpty()) {
            return;
        }

        // Validate the data set
        if (!$incidentsProcess->validate()) {
            $this->exception();

            return;
        }

        // Write the data set to database
        if (!$incidentsProcess->save()) {
            $this->exception();

            return;
        }

        Log::info(
            get_class($this).': '.
            'Queued worker has ended the processing of collector: '.$this->collector
        );
    }

    /**
     * alert administrator when problems happens. We will add the received message as attachment or bounce the original.
     *
     * @return void
     */
    protected function exception()
    {
        Log::error(
            get_class($this).': '.
            'Collector processor ending with errors.'
        );

        AlertAdmin::send(
            'AbuseIO was not able to process a collection. This the logs for PID:'.getmypid()
        );
    }
}
