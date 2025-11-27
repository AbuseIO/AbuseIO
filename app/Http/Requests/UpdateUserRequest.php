<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Rules\StringOrBoolean;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email'      => ['required', 'email',
                Rule::unique('users', 'email')->ignore($this->id),
            ],
            'password'   => ['sometimes', 'confirmed', 'min:6', 'max:32'],
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'locale'     => ['sometimes', 'required', 'min:2', 'max:3'],
            'disabled'   => ['sometimes', 'required', new StringOrBoolean],
            'roles'      => ['sometimes'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->input('id'),
        ]);
    }
}
