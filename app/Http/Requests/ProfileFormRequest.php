<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
use AbuseIO\Models\User;
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
        switch ($this->method) {
            case 'POST':
                return User::createRules($this);
            case 'PUT':
            case 'PATCH':
                // Force Authenticated user_id
                $this->id = Auth::id();
                return User::updateRules($this);
            default:
                break;
        }
    }
}
