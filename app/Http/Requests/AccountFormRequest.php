<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Account;
use AbuseIO\Traits\Api;

/**
 * Class AccountFormRequest.
 */
class AccountFormRequest extends Request
{
    use Api;

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
                return Account::createRules();
            case 'PUT':
                break;
            case 'PATCH':
                return Account::updateRules($this);
            default:
                break;
        }

        return [];
    }
}
