<?php

namespace AbuseIO\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
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
            'ticket_id' => ['required', 'integer', 'exists:tickets,id'],
            'submitter' => ['required', 'string'],
            'text'      => ['required', 'string'],
            'hidden'    => ['sometimes', 'boolean'],
            'viewed'    => ['sometimes', 'boolean'],
        ];
    }
}
