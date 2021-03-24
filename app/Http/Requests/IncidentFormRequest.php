<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Incident;

/**
 * Class IncidentFormRequest.
 */
class IncidentFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
                break;
            case 'DELETE':
                break;
            case 'POST':
                return Incident::createRules();
            case 'PUT':
                break;
            case 'PATCH':
                break;
            default:
                break;
        }

        return [];
    }

    /**
     * Transform the form results before sending it to validation.
     *
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null  $content
     */
    public function initialize(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::initialize($query, $request, $attributes, $cookies, $files, $server, $content);

        $input = \Illuminate\Support\Facades\Request::all();

        // convert the timestamp, only if it is in english time format
        if (preg_match('/^\d+$/', $input['timestamp']) != 1) {
            $timestamp = strtotime($input['timestamp']);
            if ($timestamp !== false) {
                $input['timestamp'] = $timestamp;
            }
        }

        if (!json_decode($input['information'])) {
            $input['information'] = json_encode(['report' => $input['information']]);
        }

        $this->getInputSource()->replace($input);
    }
}
