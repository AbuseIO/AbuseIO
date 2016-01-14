<?php

namespace AbuseIO\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
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
                [
                    'source'        => $incident->source,
                    'source_id'     => $incident->source_id,
                    'ip'            => $incident->ip,
                    'domain'        => $incident->domain,
                    'uri'           => $incident->uri,
                    'class'         => $incident->class,
                    'type'          => $incident->type,
                    'timestamp'     => $incident->timestamp,
                    'information'   => $incident->information,
                ],
                [
                    'source'        => 'required|string',
                    'source_id'     => 'required|stringorboolean',
                    'ip'            => 'required|ip',
                    'domain'        => 'required|stringorboolean|domain',
                    'uri'           => 'required|stringorboolean|uri',
                    'class'         => 'required|string|abuseclass',
                    'type'          => 'required|string|abusetype',
                    'timestamp'     => 'required|int|timestamp',
                    'information'   => 'required|json',
                ]
            );

            if ($validator->fails()) {
                return $this->failed(implode(' ', $validator->messages()->all()));
            }

        }

        return $this->success('');
    }
}
