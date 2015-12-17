<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
use AbuseIO\Models\Contact;
use Auth;

class ContactFormRequest extends Request
{
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        switch ($this->method) {
            case 'POST':
                return Contact::createRules($this);
            case 'PUT':
            case 'PATCH':
                return Contact::updateRules($this);
            default:
                break;
        }
    }

    /*
     * TODO: #AIO-53 Input forms should use passed request instead of grabbing input?
     * To discuss, better way to add input values manually? This way we can validate them too!
    public function initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::initialize($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->getInputSource()->add(
            [
                'account_id' => (int)Auth::user()->account->id
            ]
        );
    }
    */

}
