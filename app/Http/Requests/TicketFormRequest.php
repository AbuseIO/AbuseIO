<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Incident;
use Input;

/**
 * Class TicketFormRequest.
 */
class TicketFormRequest extends Request
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
        switch ($this->method) {
            case 'GET':
                break;
            case 'DELETE':
                break;
            case 'POST':
                return Incident::createRules();
            case 'PUT':
                break;
            case 'PATCH':
                return response('Unauthorized.', 401);
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

        $input = Input::all();
        $input['timestamp'] = strtotime($input['timestamp']);

        if (!json_decode($input['information'])) {
            $input['information'] = json_encode(['report' => $input['information']]);
        }

        $this->getInputSource()->replace($input);
    }
}
