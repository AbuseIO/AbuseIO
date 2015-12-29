<?php

namespace AbuseIO\Http\Requests;

/**
 * Class TicketsFormRequest
 * @package AbuseIO\Http\Requests
 */
class TicketsFormRequest extends Request
{
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
        return [
            //
        ];
    }
}
