<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Incident;
use AbuseIO\Models\Evidence;
use Input;

/**
 * Class TicketFormRequest
 * @package AbuseIO\Http\Requests
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
                return Incident::createRules($this);
            case 'PUT':
                break;
            case 'PATCH':
                return response('Unauthorized.', 401);
            default:
                break;
        }

        return [ ];
    }

    /**
     * Transform the form results before sending it to validation
     *
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     */
    public function initialize(
        array $query = array(),
        array $request = array(),
        array $attributes = array(),
        array $cookies = array(),
        array $files = array(),
        array $server = array(),
        $content = null
    )
    {
        parent::initialize($query, $request, $attributes, $cookies, $files, $server, $content);

        $input = Input::get();

        if (strtotime($input['timestamp']) !== false) {
            $input['timestamp'] = strtotime($input['timestamp']);
        }
        $input['information'] = json_encode(['report' => $input['information']]);

        $this->getInputSource()->replace($input);
    }
}
