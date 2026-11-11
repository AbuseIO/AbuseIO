<?php

namespace AbuseIO\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNetblockRequest extends FormRequest
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
            'first_ip' => ['required', 'ip', 'unique:netblocks,first_ip,' . $this->id . ',id,last_ip,' . $this->last_ip],
            'last_ip' => ['required', 'ip', 'unique:netblocks,last_ip,' . $this->id . ',id,first_ip,' . $this->last_ip],
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
            'id' => $this->input('id'),
            'first_ip' => $this->input('first_ip'),
            'last_ip' => $this->input('last_ip'),
        ]);
    }
}
