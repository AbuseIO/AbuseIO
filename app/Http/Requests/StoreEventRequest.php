<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Rules\TimeStamp;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'evidence_id' => ['required', 'integer', 'exists:evidences,id'],
            'source' => ['required', 'string'],
            'timestamp' => ['required', new TimeStamp],
            'information' => ['required', 'json'],
        ];
    }
}
