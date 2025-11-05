<?php

namespace AbuseIO\Http\Requests;

use AbuseIO\Models\Contact;
use AbuseIO\Traits\Api;
use Auth;

/**
 * Class ContactFormRequest.
 */
class ContactFormRequest extends Request
{
    use Api;

    /**
     * ContactFormRequest constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

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
                return Contact::createRules();
            case 'PUT':
                // Treat PUT the same as PATCH for API update endpoints
                return Contact::updateRules($this);
            case 'PATCH':
                return Contact::updateRules($this);
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
    ): void {
        parent::initialize($query, $request, $attributes, $cookies, $files, $server, $content);

        // only interesting when running in the gui
        if (!is_null(Auth::user())) {
            $input = \Illuminate\Support\Facades\Request::all();

            // force current account if the user isn't admin on the systemaccount
            if (!Auth::user()->hasRole('admin') || !Auth::user()->account->isSystemAccount()) {
                $input['account_id'] = (int) Auth::user()->account->id;
            }

            // normalize optional api_host: if empty, set to null so 'nullable|url' passes
            if (array_key_exists('api_host', $input)) {
                $apiHost = trim((string) $input['api_host']);
                if ($apiHost === '') {
                    $input['api_host'] = null;
                }
            }

            $this->getInputSource()->replace($input);
        }
    }
}
