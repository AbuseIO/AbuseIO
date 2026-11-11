<?php

namespace AbuseIO\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
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
            'domain' => ['sometimes', 'string'],
            'class_id' => ['required', 'string', 'max:100'],
            'type_id' => ['required', 'in:INFO,ABUSE,ESCALATION'],
            'ip_contact_account_id' => ['required', 'integer', 'exists:accounts,id'],
            'ip_contact_reference' => ['required', 'string'],
            'ip_contact_name' => ['required', 'string'],
            'ip_contact_email' => ['sometimes', 'emails'],
            'ip_contact_api_host' => ['nullable', 'url'],
            'ip_contact_auto_notify' => ['required', 'boolean'],
            'ip_contact_notified_count' => ['required', 'integer'],
            'domain_contact_account_id' => ['required', 'integer', 'exists:accounts,id'],
            'domain_contact_reference' => ['required', 'string'],
            'domain_contact_name' => ['required', 'string'],
            'domain_contact_email' => ['sometimes', 'emails'],
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
