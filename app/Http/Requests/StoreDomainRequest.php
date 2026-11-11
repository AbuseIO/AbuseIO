<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Rules\Domain;
use AbuseIO\Rules\StringOrBoolean;
use Illuminate\Foundation\Http\FormRequest;

class StoreDomainRequest extends FormRequest
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
            'name' => ['required', new StringOrBoolean, new Domain, 'unique:domains'],
            'contact_id' => ['required', 'integer', 'exists:contacts,id'],
            'enabled' => ['required', 'boolean'],
        ];
    }
}
