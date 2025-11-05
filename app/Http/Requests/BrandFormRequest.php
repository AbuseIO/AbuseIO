<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Brand;
use AbuseIO\Traits\Api;

/**
 * Class BrandFormRequest.
 */
class BrandFormRequest extends Request
{
    use Api;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
                break;
            case 'DELETE':
                break;
            case 'POST':
                return Brand::createRules();
            case 'PUT':
                $rules = Brand::updateRules($this);
                // For API JSON updates, allow partial updates by validating only provided fields
                if ($this->wantsJson()) {
                    return $this->filterRulesForPresentFields($rules);
                }

                return $rules;
            case 'PATCH':
                $rules = Brand::updateRules($this);
                if ($this->wantsJson()) {
                    return $this->filterRulesForPresentFields($rules);
                }

                return $rules;
            default:
                break;
        }

        return [];
    }

    /**
     * Filter validation rules to only include keys present in the request payload or uploaded files.
     * This enables partial updates over the API while preserving strict validation for provided fields.
     *
     * @param array $rules
     *
     * @return array
     */
    protected function filterRulesForPresentFields(array $rules)
    {
        $data = $this->all();

        $filtered = [];
        foreach ($rules as $field => $rule) {
            if (array_key_exists($field, $data) || $this->hasFile($field)) {
                $filtered[$field] = $rule;
            }
        }

        return $filtered;
    }
}
