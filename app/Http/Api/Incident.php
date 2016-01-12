<?php

namespace AbuseIO\Http\Api;

use AbuseIO\Http\Api\ApiCall;

use AbuseIO\Models\Incident as IncidentModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Incident extends ApiCall
{
    /**
     * Update a Ticket according to the new Incident data delivered
     * @param  integer $id Ticket ID
     * @return struct
     */
    public function update($id = null)
    {
        $data = null;

        try {
            $incident = new IncidentModel();
            $incident->ip = '127.0.0.1';
            $data = $incident;
        } catch (ModelNotFoundException $e) {
            $this->setError(1, "Couldn't create Incident");

        }
        return $this->apiReturn($data);
    }
}
