<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Note;
use Auth;

class NoteFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        switch ($this->method) {
            case 'POST':
                return Note::createRules($this);
            case 'PUT':
            case 'PATCH':
                return Note::updateRules($this);
            default:
                break;
        }
    }

    public function initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
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
            case 'PUT':
            case 'PATCH':
                $this->getInputSource()->add(
                    [
                        'submitter'     => trans('ash.communication.abusedesk'). $postingUser,
                    ]
                );
            default:
                break;
        }

    }
}
