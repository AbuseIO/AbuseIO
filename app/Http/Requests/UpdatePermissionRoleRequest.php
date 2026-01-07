<?php

namespace AbuseIO\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRoleRequest extends FormRequest
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
            'id' => ['required', 'integer', 'exists:permission_role,id'],
            'role_id' => ['required', 'integer', 'exists:roles,id', 'unique:permission_role,role_id,NULL,id,permission_id,'.$this->input('id')],
            'permission_id' => ['required', 'integer', 'exists:permissions,id', 'unique:permission_role,permission_id,NULL,id,role_id,'.$this->input('id')],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'permission_id' => $this->input('permission_id'),
            'role_id' => $this->input('role_id'),
        ]);
    }
}
