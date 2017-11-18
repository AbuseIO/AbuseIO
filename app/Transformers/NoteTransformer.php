<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\Note;
use League\Fractal\TransformerAbstract;

class NoteTransformer extends TransformerAbstract
{
    /**
     * converts the note object to a generic array.
     *
     * @param Note $note
     *
     * @return array
     */
    public function transform(Note $note)
    {
        return [
            'id'        => (int) $note->id,
            'ticket_id' => (int) $note->ticket_id,
            'submitter' => (string) $note->submitter,
            'text'      => (string) $note->text,
            'hidden'    => (bool) $note->hidden,
            'viewed'    => (bool) $note->viewed,
        ];
    }
}
