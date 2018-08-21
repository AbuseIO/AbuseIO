<?php

namespace AbuseIO\Jobs;

use AbuseIO\Models\Event;
use Log;

/**
 * This IncidentsProcess class handles incidents after processing
 * This will provide centralized calling of the Validator, Saver, EvidenceSave, etc
 * and can be called by HTTP/API/CLI to manually add incidents.
 *
 * Class IncidentsProcess
 */
class IncidentsProcess extends Job
{
    /**
     * @var array
     */
    private $incidents;

    /**
     * @var \AbuseIO\Models\Evidence
     */
    private $evidence;

    /**
     * @var \AbuseIO\Models\Origin
     */
    private $origin;

    /**
     * Create a new command instance.
     *
     * @param array                    $incidents
     * @param \AbuseIO\Models\Evidence $evidence
     * @param \AbuseIO\Models\Origin   $origin
     */
    public function __construct($incidents, $evidence, $origin = null)
    {
        $this->incidents = $incidents;
        $this->evidence = $evidence;
        $this->origin = $origin;
    }

    /**
     * Checks if the incidents set is not empty.
     *
     * @return bool
     */
    public function notEmpty()
    {
        if (count($this->incidents) === 0) {
            Log::warning(
                get_class($this).': '.
                'Empty set of incidents therefore skipping validation and saving'
            );

            return false;
        }

        return true;
    }

    /**
     *  Wrapper for validate data.
     *
     * @return bool
     */
    public function validate()
    {
        // Call validator
        $validator = new IncidentsValidate();
        $validatorResult = $validator->check($this->incidents);

        if ($validatorResult['errorStatus'] === true) {
            Log::error(
                get_class($validator).': '.
                'Validator has ended with errors ! : '.$validatorResult['errorMessage']
            );

            return false;
        }

        // Todo validate evidence too

        Log::info(
            get_class($validator).': '.
            'Validator has ended without errors'
        );

        return true;
    }

    /**
     * Wrapper for save data.
     *
     * @return bool
     */
    public function save()
    {
        /*
         * save evidence into table
         **/
        $this->evidence->save();

        /*
         * call saver
         **/
        $saver = new IncidentsSave();
        $saverResult = $saver->save($this->incidents, $this->evidence->id);

        /*
         * We've hit a snag, so we are gracefully killing ourselves
         * after we contact the admin about it. IncidentsSave should never
         * end with problems unless the mysql died while doing transactions
         **/
        if ($saverResult['errorStatus'] === true) {
            Log::error(
                get_class($saver).': '.
                'Saver has ended with errors ! : '.$saverResult['errorMessage']
            );

            return false;
        }

        $linkedEvents = Event::where('evidence_id', '=', $this->evidence->id);
        if ($linkedEvents->count() == 0) {
            Log::info(
                get_class($saver).': '.
                'The evidence submitted was never linked to any ticket, thus removing it from the DB again'
            );

            $this->evidence->forceDelete();
        }

        Log::info(
            get_class($saver).': '.
            'Saver has ended without errors'
        );

        return true;
    }
}
