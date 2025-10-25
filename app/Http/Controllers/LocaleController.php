<?php

namespace AbuseIO\Http\Controllers;

use Session;
use URL;
use Validator;

/**
 * Class LocaleController.
 */
class LocaleController extends Controller
{
    /**
     * Change the locale.
     *
     * @param string $locale default 'en'
     *
     * @return \Illuminate\Http\Response
     */
    public function setLocale($locale = 'en')
    {
        $rules = [
            'locale' => 'in:en,nl,gr', // List of supported locales
        ];

        $validator = Validator::make(['locale' => $locale], $rules);

        // update the locale setting in the user
        if (!empty($this->auth_user)) {
            $this->auth_user->locale = $locale;
            $this->auth_user->save();
        }

        if ($validator->passes()) {
            Session::put('locale', $locale);
        }

        return redirect(url(URL::previous()));
    }
}
