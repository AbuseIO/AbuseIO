<?php

namespace AbuseIO\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class BaseContactRequest extends FormRequest
{
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
