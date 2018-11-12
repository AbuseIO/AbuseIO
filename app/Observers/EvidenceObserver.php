<?php

namespace AbuseIO\Observers;

use AbuseIO\Models\Evidence;
use Exception;
use Log;
use Storage;

class EvidenceObserver
{
    /**
     * Deleting event observer.
     *
     * When we delete an evidence, also delete the linked mail
     *
     * @param Evidence $evidence
     */
    public function deleting(Evidence $evidence)
    {
        // always delete evidence, even when an error occurs deleting the filename
        try {
            Storage::delete($evidence->filename);
        } catch (Exception $e) {
            // Log what went wrong
            Log::info("Couldn't delete {$evidence->filename} for Evidence: {$evidence->id} >> ".$e->getMessage());
        }
    }
}
