<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Brand;

/**
 * Class BrandFormRequest
 * @package AbuseIO\Http\Requests
 */
class BrandFormRequest extends Request
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
                break;
            case 'DELETE':
                break;
            case 'POST':
                return Brand::createRules();
            case 'PUT':
                break;
            case 'PATCH':
                return Brand::updateRules($this);
            default:
                break;
        }

        return [ ];
    }
}
