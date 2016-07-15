<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\Incident;
use League\Fractal\TransformerAbstract;

class IncidentTransformer extends TransformerAbstract
{
    /**
     * converts the incident object to a generic array.
     *
     * @param Incident incident
     *
     * @return array
     */
    public function transform(Incident $incident)
    {
        return [
            'source'                => (string) $incident->source,
            'source_id'             => (string) $incident->source_id,
            'ip'                    => (string) $incident->ip,
            'domain'                => (string) $incident->domain,
            'timestamp'             => (int) $incident->timestamp,
            'class'                 => (string) $incident->class,
            'type'                  => (string) $incident->type,
            'information'           => (string) $incident->information,
        ];
    }
}
