<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Contact;
use Auth;

/**
 * Class ContactFormRequest.
 */
class ContactFormRequest extends Request
{
    /**
     * ContactFormRequest constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

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
                return Contact::createRules();
            case 'PUT':
                break;
            case 'PATCH':
                return Contact::updateRules($this);
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

        $this->getInputSource()->add(
            [
                'account_id' => (int) Auth::user()->account->id,
            ]
        );
    }
}
