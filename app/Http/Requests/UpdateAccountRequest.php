<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Rules\StringOrBoolean;
use AbuseIO\Rules\UniqueFlag;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'unique:accounts,name,'.$this->id],
            'brand_id'      => ['required','integer', 'exists:brands,id'],
            'systemaccount' => ['sometimes', 'required', new UniqueFlag("accounts", "systemaccount")],
            'disabled'      => ['sometimes', 'required', new StringOrBoolean], // disabled is sent as a string
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
