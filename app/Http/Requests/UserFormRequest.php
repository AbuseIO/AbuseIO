<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\User;

/**
 * Class UserFormRequest.
 */
class UserFormRequest extends Request
{
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
        switch ($this->method()) {
            case 'GET':
                break;
            case 'DELETE':
                break;
            case 'POST':
                return User::createRules();
            case 'PUT':
                break;
            case 'PATCH':
                return User::updateRules($this);
            default:
                break;
        }

        return [];
    }
}
