<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Domain;
use AbuseIO\Traits\Api;

/**
 * Class DomainFormRequest.
 */
class DomainFormRequest extends Request
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
                return Domain::createRules();
            case 'PUT':
                break;
            case 'PATCH':
                return Domain::updateRules($this);
            default:
                break;
        }

        return [];
    }
}
