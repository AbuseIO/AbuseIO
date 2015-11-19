<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;

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
        switch ($this->method) {
            case 'POST':
                return [
                    'name'              => 'required|unique:brands,name',
                    'company_name'      => 'required',
                    'introduction_text' => 'required',
                    'logo'              => 'required'
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'name'              => 'required|unique:brands,name,'. $this->id,
                    'company_name'      => 'required',
                    'introduction_text' => 'required',
                ];
            default:
                break;
        }
    }
}
