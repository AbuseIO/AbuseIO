<?php

namespace AbuseIO\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class BaseProfileRequest extends FormRequest
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

        $this->getInputSource()->add(
            [
                'id'         => (int) Auth::id(),
                'account_id' => (int) Auth::user()->account->id,
            ]
        );
    }
}
