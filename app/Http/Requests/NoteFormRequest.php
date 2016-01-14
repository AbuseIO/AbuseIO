<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Note;
use Auth;

/**
 * Class NoteFormRequest
 * @package AbuseIO\Http\Requests
 */
class NoteFormRequest extends Request
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

        return [ ];
    }

    /**
     * Transform the form results before sending it to validation
     *
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     */
    public function initialize(
        array $query = array(),
        array $request = array(),
        array $attributes = array(),
        array $cookies = array(),
        array $files = array(),
        array $server = array(),
        $content = null
    ) {
        parent::initialize($query, $request, $attributes, $cookies, $files, $server, $content);

        if (config('main.notes.show_abusedesk_names') === true) {
            $postingUser = ' (' . Auth::user()->fullName() . ')';
        } else {
            $postingUser = '';
        }

        switch ($this->method()) {
            case 'POST':
                $this->getInputSource()->add(
                    [
                        'submitter'     => trans('ash.communication.abusedesk'). $postingUser,
                        'viewed'        => true,
                    ]
                );
                break;
            case 'PATCH':
                $this->getInputSource()->add(
                    [
                        'submitter'     => trans('ash.communication.abusedesk'). $postingUser,
                    ]
                );
                break;
            default:
                break;
        }

    }
}
