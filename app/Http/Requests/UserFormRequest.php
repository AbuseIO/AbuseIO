<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserFormRequest.
 */
class UserFormRequest extends FormRequest
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
                return User::createRules();
            case 'PUT':
                break;
            case 'PATCH':
                return User::updateRules($this);
            default:
                break;
        }

        return [];
    }

    /**
     * Manipulate the data before we send it to the validator.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        $data = $this->all();
        if (array_key_exists('disabled', $data)) {
            $data['disabled'] = filter_var($data['disabled'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $data['disabled'] = false;
        }

        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
