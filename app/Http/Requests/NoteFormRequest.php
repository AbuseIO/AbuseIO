<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Note;
use AbuseIO\Traits\Api;

/**
 * Class NoteFormRequest.
 */
class NoteFormRequest extends Request
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
                return Note::createRules();
            case 'PUT':
                break;
            case 'PATCH':
                return Note::updateRules();
            default:
                break;
        }

        return [];
    }
}
