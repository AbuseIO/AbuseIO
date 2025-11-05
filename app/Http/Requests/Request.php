<?php

namespace AbuseIO\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use AbuseIO\Api\ErrorCodes;

/**
 * Class Request.
 */
abstract class Request extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * Ensures API requests return the unified error format expected by tests
     * with code ERR_WRONGARGS and message payload.
     *
     * @param Validator $validator
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->wantsJson()) {
            $errors = $validator->errors()->all();

            $payload = [
                'data'    => [],
                'message' => [
                    'code'      => ErrorCodes::CODE_WRONG_ARGS,
                    'message'   => implode(' ', $errors),
                    'http_code' => 422,
                    'success'   => false,
                ],
            ];

            throw new HttpResponseException(response()->json($payload, 422));
        }

        parent::failedValidation($validator);
    }
}
