<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
use AbuseIO\Models\User;

class UserFormRequest extends Request
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
                    'first_name'    => 'required',
                    'last_name'     => 'sometimes|required',
                    'email'         => 'required|email|unique:users,email',
                    'password'      => 'sometimes|confirmed|min:6',
                    'account_id'    => 'required',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'first_name'    => 'required',
                    'last_name'     => 'sometimes|required',
                    'email'         => 'required|email|unique:users,email,'. $this->id,
                    'password'      => 'sometimes|confirmed|min:6',
                    'account_id'    => 'required',
                ];
            default:
                break;
        }
    }
}
