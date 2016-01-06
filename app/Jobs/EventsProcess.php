<?php

namespace AbuseIO\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Log;

/**
 * This EventsProcess class handles events after processing
 * This will provide centralized calling of the Validator, Saver, EvidenceSave, etc
 * and can be called by HTTP/API/CLI to manually add events
 *
 * Class EventsProcess
 */
class EventsProcess extends Job implements SelfHandling
{

    /**
     * @var array
     */
    private $events;

    /**
     * @var \AbuseIO\Models\Evidence
     */
    private $evidence;

    /**
     * Create a new command instance.
     *
     * @param array $events
     * @param \AbuseIO\Models\Evidence $evidence
     */
    public function __construct($events, $evidence)
    {
        $this->events   = $events;
        $this->evidence = $evidence;
    }

    /**
     * Execute the command.
     *
     * @return boolean
     */
    public function fire()
    {

        if (count($this->events) !== 0) {
            // Call validator
            $validator = new EventsValidate();
            $validatorResult = $validator->check($this->events);

            if ($validatorResult['errorStatus'] === true) {
                Log::error(
                    get_class($validator) . ': ' .
                    'Validator has ended with errors ! : ' . $validatorResult['errorMessage']
                );

                return false;
            } else {
                Log::info(
                    get_class($validator) . ': ' .
                    'Validator has ended without errors'
                );
            }

            /**
             * save evidence into table
             **/
            $this->evidence->save();

            /**
             * call saver
             **/
            $saver = new EventsSave();
            $saverResult = $saver->save($this->events, $this->evidence->id);

            /**
             * We've hit a snag, so we are gracefully killing ourselves
             * after we contact the admin about it. EventsSave should never
             * end with problems unless the mysql died while doing transactions
             **/
            if ($saverResult['errorStatus'] === true) {
                Log::error(
                    get_class($saver) . ': ' .
                    'Saver has ended with errors ! : ' . $saverResult['errorMessage']
                );

                return false;
            } else {
                Log::info(
                    get_class($saver) . ': ' .
                    'Saver has ended without errors'
                );
            }
        } else {
            Log::warning(
                get_class($this) . ': ' .
                'Empty set of events therefore skipping validation and saving'
            );
        }

        return true;
    }
}
