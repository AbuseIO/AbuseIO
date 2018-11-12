<?php

namespace AbuseIO\Observers;

use AbuseIO\Models\Evidence;
use Storage;

class EvidenceObserver
{
    /**
     * Deleting event observer
     *
     * When we delete an evidence, also delete the linked mail
     *
     * @param Evidence $evidence
     */
    public function deleting(Evidence $evidence)
    {
        Storage::delete($evidence->filename);
    }
}