<?php

namespace AbuseIO\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Uri implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!filter_var(
                'http://test.for.var.com'.$value,
                FILTER_VALIDATE_URL
            ) !== false) {
            $fail(trans('validation.uri', ['attribute' => $attribute]));
        }
    }
}
