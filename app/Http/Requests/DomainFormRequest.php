<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;

class DomainFormRequest extends Request
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
                    'name'       => 'required|unique:domains',
                    'contact_id' => 'required|integer',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'name'       => 'required|unique:domains,name,'. $this->id,
                    'contact_id' => 'required|integer',
                ];
            default:
                break;
        }
    }
}
