<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
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
                return [
                    'reference' => 'required|unique:contacts,reference',
                    'name'      => 'required',
                    'email'     => 'required|emails',
                    'rpc_host'  => 'sometimes|url',
                    'enabled'   => 'required|boolean',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'reference' => 'required|unique:contacts,reference,'. $this->id,
                    'name'      => 'required',
                    'email'     => 'required|emails',
                    'rpc_host'  => 'sometimes|url',
                    'enabled'   => 'required|boolean',
                ];
            default:
                break;
        }
    }
}
