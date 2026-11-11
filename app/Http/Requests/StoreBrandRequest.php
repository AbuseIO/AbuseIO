<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Rules\BladeTemplate;
use AbuseIO\Rules\UniqueFlag;
use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
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
            'name' => ['required', 'unique:brands,name'],
            'company_name' => ['required'],
            'introduction_text' => ['required'],
            'logo' => ['required', 'image', 'max:64'],
            'creator_id' => ['required', 'integer', 'exists:accounts,id'],
            'systembrand' => ['sometimes', 'required', new UniqueFlag("brands", "systembrand")],
            'mail_template_plain' => ['sometimes', 'required', new BladeTemplate],
            'mail_template_html' => ['sometimes', 'required', new BladeTemplate],
            'ash_template' => ['sometimes', 'required', new BladeTemplate],
        ];
    }
}
