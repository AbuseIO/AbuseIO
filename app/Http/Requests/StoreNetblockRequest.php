<?php

namespace AbuseIO\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNetblockRequest extends FormRequest
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
            'first_ip' => [
                'required',
                'ip',
                Rule::unique('netblocks', 'first_ip')
                    ->where('last_ip', $this->last_ip)
                    ->ignore(null, 'id'),
            ],
            'last_ip' => [
                'required',
                'ip',
                Rule::unique('netblocks', 'last_ip')
                    ->where('first_ip', $this->first_ip)
                    ->ignore(null, 'id'),
            ],
            'contact_id' => ['required', 'integer', 'exists:contacts,id'],
            'description' => ['required'],
            'enabled' => ['required', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_ip' => $this->input('first_ip'),
            'last_ip' => $this->input('last_ip'),
        ]);
    }

}
