<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Ticket;
use AbuseIO\Traits\Api;

/**
 * Class TicketFormRequest.
 */
class TicketFormRequest extends Request
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
                return Ticket::createRules();
                break;
            case 'PUT':
                break;
            case 'PATCH':
                return Ticket::updateRules($this);
                break;
            default:
                break;
        }

        return [];
    }
}
