<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Rules\Domain;
use AbuseIO\Rules\Email;
use AbuseIO\Rules\StringOrBoolean;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
            'ip' => ['required', 'ip'],
            'domain' => ['nullable', new StringOrBoolean, new Domain],
            'class_id' => ['required', 'string', 'max:100'],
            'type_id' => ['required', 'in:INFO,ABUSE,ESCALATION'],
            'ip_contact_account_id' => ['required', 'integer', 'exists:accounts,id'],
            'ip_contact_reference' => ['required', 'string'],
            'ip_contact_name' => ['required', 'string'],
            'ip_contact_email' => ['sometimes', new Email],
            'ip_contact_api_host' => ['nullable', 'url'],
            'ip_contact_auto_notify' => ['required', 'boolean'],
            'ip_contact_notified_count' => ['required', 'integer'],
            'domain_contact_account_id' => ['required', 'integer', 'exists:accounts,id'],
            'domain_contact_reference' => ['required', 'string'],
            'domain_contact_name' => ['required', 'string'],
            'domain_contact_email' => ['sometimes', new Email],
            'domain_contact_api_host' => ['nullable', 'url'],
            'domain_contact_auto_notify' => ['required', 'boolean'],
            'domain_contact_notified_count' => ['required', 'integer'],
            'status_id' => ['required', 'in:OPEN,CLOSED,ESCALATED,RESOLVED,IGNORED'],
            'contact_status_id' => ['sometimes', 'in:OPEN,RESOLVED,IGNORED'],
            'last_notify_count' => ['required', 'integer'],
            'last_notify_timestamp' => ['required', 'timestamp'],
        ];
    }
}
