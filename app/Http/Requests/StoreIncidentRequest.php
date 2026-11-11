<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Rules\AbuseClass;
use AbuseIO\Rules\AbuseType;
use AbuseIO\Rules\StringOrBoolean;
use AbuseIO\Rules\TimeStamp;

class StoreIncidentRequest extends BaseIncidentRequest
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
            'source'      => ['required', 'string'],
            'source_id'   => ['nullable', new StringOrBoolean],
            'ip'          => ['required', 'ip'],
            'domain'      => ['nullable', new StringOrBoolean, 'string'],
            'timestamp'   => ['required', new TimeStamp],
            'class'       => ['required', new AbuseClass],
            'type'        => ['required', new AbuseType],
            'information' => ['required', 'json'],
        ];
    }
}
