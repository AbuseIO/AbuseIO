<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
use AbuseIO\Models\User;

class AccountFormRequest extends Request
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
                    'name'       => 'required|unique:accounts',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'name'       => 'required|unique:accounts,name,'. $this->id,
                ];
            default:
                break;
        }
    }
}
