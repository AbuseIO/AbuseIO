<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use Validator;
use Session;
use URL;

class LocaleController extends Controller
{
    /**
     * Change the locale
     *
     * @return \Illuminate\Http\Response
     */
    public function setLocale($locale = 'en')
    {
        $rules = [
            'locales' => 'in:en,nl' // List of supported locales
        ];

        $validator = Validator::make(compact($locale), $rules);

        if ($validator->passes()) {
            Session::put('locale', $locale);
            return redirect(url(URL::previous()));
        }
    }
}
