<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Note;
use AbuseIO\Traits\Api;
use Auth;

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
        switch ($this->method) {
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

    /**
     * Transform the form results before sending it to validation.
     *
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null  $content
     */
    public function initialize(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::initialize($query, $request, $attributes, $cookies, $files, $server, $content);

        if (config('main.notes.show_abusedesk_names') === true and !Auth::guest()) {
            $postingUser = ' (' . Auth::user()->fullName().')';
        } else {
            $postingUser = '';
        }

        switch ($this->method()) {
            case 'POST':
                $this->getInputSource()->add(
                    [
                        'submitter'     => trans('ash.communication.abusedesk').$postingUser,
                        'viewed'        => true,
                    ]
                );
                break;
            case 'PATCH':
                $this->getInputSource()->add(
                    [
                        'submitter'     => trans('ash.communication.abusedesk').$postingUser,
                    ]
                );
                break;
            default:
                break;
        }
    }
}
