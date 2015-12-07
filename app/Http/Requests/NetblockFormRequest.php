<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
use AbuseIO\Models\Netblock;

class NetblockFormRequest extends Request
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
        $netblock = new Netblock();

        switch ($this->method) {
            case 'POST':
                return $netblock->createRules($this);
            case 'PUT':
            case 'PATCH':
                return $netblock->updateRules($this);
            default:
                break;
        }
    }
}
