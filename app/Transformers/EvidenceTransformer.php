<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\Evidence;
use League\Fractal\TransformerAbstract;

class EvidenceTransformer extends TransformerAbstract
{
    /**
     * converts the evidence object to a generic array.
     *
     * @param Evidence $evidence
     *
     * @return array
     */
    public function transform(Evidence $evidence)
    {
        return [
            'id'       => (int) $evidence->id,
            'filename' => (string) $evidence->filename,
            'sender'   => (string) $evidence->sender,
            'subject'  => (string) $evidence->subject,

            // not sure if we want to return this by default
            //'data'      => $evidence->data,

        ];
    }
}
