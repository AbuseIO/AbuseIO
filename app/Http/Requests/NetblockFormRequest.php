<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Netblock;

/**
 * Class NetblockFormRequest
 * @package AbuseIO\Http\Requests
 */
class NetblockFormRequest extends Request
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
        switch ($this->method) {
            case 'GET':
            case 'DELETE':
            case 'POST':
                return Netblock::createRules($this);
            case 'PUT':
            case 'PATCH':
                return Netblock::updateRules($this);
            default:
                break;
        }

        return [ ];
    }
}
