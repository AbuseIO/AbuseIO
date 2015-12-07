<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
use AbuseIO\Models\Account;

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
        $account = new Account();

        switch ($this->method) {
            case 'POST':
                return $account->createRules($this);
            case 'PUT':
            case 'PATCH':
                return $account->updateRules($this);
            default:
                break;
        }
    }
}
