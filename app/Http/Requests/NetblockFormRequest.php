<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Http\Requests\Request;

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
                     */
                    'first_ip'      => "required|ip|unique:netblocks,first_ip,NULL,id,last_ip,{$this->last_ip}",
                    'last_ip'       => "required|ip|unique:netblocks,last_ip,NULL,id,first_ip,{$this->first_ip}",
                    'contact_id'    => 'required|integer',
                    'description'   => 'required',
                    'enabled'       => 'required|boolean',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'first_ip'      => "required|ip|unique:netblocks,first_ip,{$this->id},id,last_ip,{$this->last_ip}",
                    'last_ip'       => "required|ip|unique:netblocks,last_ip,{$this->id},id,first_ip,{$this->first_ip}",
                    'contact_id'    => 'required|integer',
                    'description'   => 'required',
                    'enabled'       => 'required|boolean',
                ];
            default:
                break;
        }
    }
}
