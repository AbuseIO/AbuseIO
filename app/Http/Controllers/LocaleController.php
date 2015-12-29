<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use Validator;
use Session;
use URL;

/**
 * Class LocaleController
 * @package AbuseIO\Http\Controllers
 */
class LocaleController extends Controller
{
    /**
     * Change the locale
     *
     * @param string $locale default 'en'
     * @return \Illuminate\Http\Response
     */
    public function setLocale($locale = 'en')
    {
        $rules = [
            'locales' => 'in:en,nl' // List of supported locales
        ];

        $validator = Validator::make(compact($locale), $rules);

        // update the locale setting in the user
        $this->auth_user->locale = $locale;
        $this->auth_user->save();

        if ($validator->passes()) {
            Session::put('locale', $locale);
        }

        return redirect(url(URL::previous()));

    }
}
