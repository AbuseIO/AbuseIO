<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\Event;
use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract
{
    /**
     * converts the event object to a generic array.
     *
     * @param Event $event
     *
     * @return array
     */
    public function transform(Event $event)
    {
        // null evidences shouldn't go through the evidence transformer
        $evidence = [];
        if ($event->evidence) {
            $evidence = (new EvidenceTransformer())->transform($event->evidence);
        }

        return [
            'id'          => (int) $event->id,
            'ticket_id'   => (int) $event->ticket_id,
            'evidence_id' => (int) $event->evidence_id,
            'source'      => (string) $event->source,
            'timestamp'   => (int) $event->timestamp,
            'information' => (string) $event->information,
            'evidence'    => $evidence,
        ];
    }
}
