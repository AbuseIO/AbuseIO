<?php

namespace AbuseIO\Jobs;

use AbuseIO\Collectors\Factory as CollectorFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Filesystem\Filesystem;
use AbuseIO\Models\Evidence;
use AbuseIO\Jobs\AlertAdmin;
use Config;
use Carbon;
use Uuid;
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
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
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

        if (count($collectorResult['data']) !== 0) {
            // Call validator
            $validator = new EventsValidate();
            $validatorResult = $validator->check($collectorResult['data']);

            if ($validatorResult['errorStatus'] === true) {
                Log::error(
                    '(JOB ' . getmypid() . ') ' . get_class($validator) . ': ' .
                    'Validator has ended with errors ! : ' . $validatorResult['errorMessage']
                );

                $this->exception();
                return;
            } else {
                Log::info(
                    '(JOB ' . getmypid() . ') ' . get_class($validator) . ': ' .
                    'Validator has ended without errors'
                );
            }

            /**
             * save evidence onto disk
             */
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
                    $this->exception();
                }
            }

            if ($filesystem->isFile($path . $file)) {
                Log::error(
                    '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                    'File aready exists: ' . $filename
                );
                $this->exception();
            }

            if ($filesystem->put(
                $path . $file,
                json_encode(
                    [
                        'collectorName' => $this->collector,
                        'collectorData' => $collectorResult,
                    ]
                )
            ) === false) {
                Log::error(
                    '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                    'Unable to write file: ' . $filename
                );

                $this->exception();
            }

            /**
             * save evidence into table
             **/
            $evidence = new Evidence();
            $evidence->filename = $filename;
            $evidence->sender = 'abuse@localhost';
            $evidence->subject = "CLI Collector {$this->collector}";
            $evidence->save();

            /**
             * call saver
             **/
            $saver = new EventsSave();
            $saverResult = $saver->save($collectorResult['data'], $evidence->id);

            /**
             * We've hit a snag, so we are gracefully killing ourselves
             * after we contact the admin about it. EventsSave should never
             * end with problems unless the mysql died while doing transactions
             **/
            if ($saverResult['errorStatus'] === true) {
                Log::error(
                    '(JOB ' . getmypid() . ') ' . get_class($saver) . ': ' .
                    'Saver has ended with errors ! : ' . $saverResult['errorMessage']
                );

                $this->exception();
                return;
            } else {
                Log::info(
                    '(JOB ' . getmypid() . ') ' . get_class($saver) . ': ' .
                    'Saver has ended without errors'
                );
            }
        } else {
            Log::warning(
                '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
                'Collector did not return any events therefore skipping validation and saving a empty event set'
            );
        }

        Log::info(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
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
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            'Collector processor ending with errors.'
        );

        AlertAdmin::send(
            'AbuseIO was not able to process a collection. This the logs for PID:' . getmypid()
        );
    }
}
