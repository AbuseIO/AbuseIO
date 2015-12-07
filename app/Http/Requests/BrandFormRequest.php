<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
use AbuseIO\Models\Brand;

class BrandFormRequest extends Request
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
        $brand = new Brand();

        switch ($this->method) {
            case 'POST':
                return $brand->createRules($this);
            case 'PUT':
            case 'PATCH':
                return $brand->updateRules($this);
            default:
                break;
        }
    }
}
