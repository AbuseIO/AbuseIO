<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Netblock;
use AbuseIO\Traits\Api;

/**
 * Class NetblockFormRequest.
 */
class NetblockFormRequest extends Request
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
                return Netblock::createRules($this);
            case 'PUT':
                break;
            case 'PATCH':
                return Netblock::updateRules($this);
            default:
                break;
        }

        return [];
    }
}
