<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;

class ContactFormRequest extends Request
{
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
                    'email'     => 'required|email',
                    'rpc_host'  => 'sometimes|url',
                    'enabled'   => 'required|boolean',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'reference' => 'required|unique:contacts,reference,'. $this->id,
                    'name'      => 'required',
                    'email'     => 'required|email',
                    'rpc_host'  => 'sometimes|url',
                    'enabled'   => 'required|boolean',
                ];
            default:
                break;
        }
    }
}
