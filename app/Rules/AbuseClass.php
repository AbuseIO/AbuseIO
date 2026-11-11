<?php

namespace AbuseIO\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AbuseClass implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (classificationLookup($value) === null) {
            $fail(trans('validation.abuseclass', ['attribute' => $attribute]));
        }
    }
}
