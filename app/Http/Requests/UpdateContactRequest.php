<?php

namespace AbuseIO\Http\Requests;

class UpdateContactRequest extends BaseContactRequest
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
            'reference'  => ['required', 'string', 'unique:contacts,reference,' . $this->id],
            'name'       => ['required'],
            'email'      => ['sometimes', 'email'],
            'api_host'   => ['nullable', 'url'],
            'enabled'    => ['required', 'boolean'],
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
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
