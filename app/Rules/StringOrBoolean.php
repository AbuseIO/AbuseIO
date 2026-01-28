<?php

namespace AbuseIO\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class StringOrBoolean implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) && !in_array($value, [true, false, 1, 0, "1", "0", "true", "false"], true)) {
            $fail(trans('validation.stringorboolean'));
        }
    }
}
