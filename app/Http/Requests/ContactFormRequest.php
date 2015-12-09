<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
use AbuseIO\Models\Contact;
use Validator;

class ContactFormRequest extends Request
{
    public function __construct()
    {
        parent::__construct();

        Validator::extend(
            'emails',
            function ($attribute, $value, $parameters) {
                $rules = [
                    'email' => 'required|email',
                ];

                $value = explode(',', $value);

                foreach ($value as $email) {
                    $data = [
                        'email' => $email
                    ];
                    $validator = Validator::make($data, $rules);
                    if ($validator->fails()) {
                        return false;
                    }
                }

                return true;
            }
        );
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
}
