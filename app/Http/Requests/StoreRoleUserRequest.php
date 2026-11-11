<?php

namespace AbuseIO\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role_id' => ['required', 'integer', 'exists:roles,id', 'unique:role_user,role_id,NULL,id,user_id,'.$this->user_id],
            'user_id' => ['required', 'integer', 'exists:users,id', 'unique:role_user,user_id,NULL,id,role_id,'.$this->role_id],
        ];
    }
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->input('user_id'),
            'role_id' => $this->input('role_id'),
        ]);
    }
}
