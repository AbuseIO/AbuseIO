<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;
use ICF;

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
        switch ($this->method) {
            case 'POST':
                return [
                    /*
                     * TODO : marksg: not working as intended (yet)
                     * For now, no mixed unique validation
                     */
                    //'first_ip'      => 'required|ip|unique:netblocks,first_ip,last_ip,'.$this->last_ip.',first_ip,'.$this->first_ip,
                    //'first_ip'      => 'required|ip|unique_with:netblocks,last_ip',
                    'first_ip'      => 'required|ip',
                    'last_ip'       => 'required|ip',
                    'contact_id'    => 'required|integer',
                    'description'   => 'required',
                    'enabled'       => 'required|boolean',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'first_ip'      => 'required|ip|unique:netblocks,first_ip,'. $this->id,
                    'last_ip'       => 'required|ip',
                    'contact_id'    => 'required|integer',
                    'description'   => 'required',
                    'enabled'       => 'required|boolean',
                ];
            default:
                break;
        }
    }
}
