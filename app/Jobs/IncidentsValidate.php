<?php

namespace AbuseIO\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use AbuseIO\Models\Incident;
use Validator;

/**
 * This IncidentsValidate class handles validation of multiple incidents
 *
 * Class IncidentsValidate
 */
class IncidentsValidate extends Job implements SelfHandling
{
    public $incidents;

    /**
     * Create a new command instance.
     *

     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the command.
     *
     * @return array
     * @param array $incidents
     */
    public function check($incidents)
    {
        if (empty($incidents)) {
            return $this->failed("Empty resultset cannot be validated");
        }

        foreach ($incidents as $incident) {

            if (!is_object($incident)) {
                return $this->failed("Parser did not gave the correct incident objects in an array");
            }

            $validator = Validator::make(
                $incident->toArray(),
                Incident::createRules()
            );

            if ($validator->fails()) {
                return $this->error(implode(' ', $validator->messages()->all()));
            }

        }

        return $this->success('');
    }
}
