<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Rules\StringOrBoolean;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name'  => ['required', 'string'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'confirmed', 'min:6', 'max:32'],
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'locale'     => ['required', 'min:2', 'max:3'],
            'disabled'   => ['required', new StringOrBoolean], // disabled is sent as a string
            'roles'      => ['sometimes'],
        ];
    }
}
