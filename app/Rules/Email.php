<?php

namespace AbuseIO\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Email implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $emails = explode(',', $value);

        foreach ($emails as $email) {
            $email = [
                'email' => trim($email),
            ];
            $rules = [
                'email' => 'required|email'
            ];
            $validator = \Validator::make($email, $rules);
            if ($validator->fails()) {
                $fail(trans('validation.emails', ['attribute' => $attribute]));
            }

        }
    }
}
