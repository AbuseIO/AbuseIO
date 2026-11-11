<?php

namespace AbuseIO\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseIncidentRequest extends FormRequest
{
    public function initialize(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
              $content = null
    ): void {
        parent::initialize($query, $request, $attributes, $cookies, $files, $server, $content);

        $input = \Illuminate\Support\Facades\Request::all();

        // convert the timestamp, only if it is in english time format
        if (isset($input['timestamp']) && preg_match('/^\d+$/', $input['timestamp']) != 1) {
            $timestamp = strtotime($input['timestamp']);
            if ($timestamp !== false) {
                $input['timestamp'] = $timestamp;
            }
        }

        if (isset($input['information']) && !json_decode($input['information'])) {
            $input['information'] = json_encode(['report' => $input['information']]);
        }

        $this->getInputSource()->replace($input);
    }
}
