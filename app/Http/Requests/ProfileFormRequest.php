<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
use Auth;

class ProfileFormRequest extends Request
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
        return [
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|unique:users,email,' . Auth::id() .'|email',
            'password'   => 'sometimes|confirmed|min:6'
        ];
    }
}
