<?php

namespace AbuseIO\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TimeStamp implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // early fail if it is a string and contains non-numeric characters
        if (is_string($value) && !ctype_digit($value)) {
            $fail(trans('validation.timestamp', ['attribute' => $attribute]));
        }

        $check = (is_int($value) ? $value : (int) $value);

        if ($check > PHP_INT_MAX || $check < ~PHP_INT_MAX) {
            $fail(trans('validation.timestamp', ['attribute' => $attribute]));
        }
    }
}
