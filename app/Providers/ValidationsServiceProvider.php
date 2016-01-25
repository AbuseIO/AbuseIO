<?php namespace AbuseIO\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;
use Lang;

/**
 * Extends the default laravel validations
 *
 * Class ValidationsServiceProvider
 * @package AbuseIO\Providers
 */
class ValidationsServiceProvider extends ServiceProvider
{

    /**
     * Contains the added validations centralized on a single loaded place
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Add timestamp validation
         */
        /** @noinspection PhpUnusedParameterInspection */
        Validator::extend(
            'timestamp',
            function ($attribute, $value, $parameters, $validator) {
                $check = (is_int($value) or is_float($value))
                    ? $value
                    : (string) (int) $value;

                return ($check === $value)
                && ($value <= PHP_INT_MAX)
                && ($value >= ~PHP_INT_MAX);
            }
        );

        /*
         * Add validation for multiple comma seperated e-mails
         */
        /** @noinspection PhpUnusedParameterInspection */
        Validator::extend(
            'emails',
            function ($attribute, $value, $parameters, $validator) {
                $rules = [
                    'email' => 'required|email',
                ];

                $value = explode(',', $value);

                foreach ($value as $email) {
                    $data = [
                        'email' => $email
                    ];

                    $validator = Validator::make($data, $rules);

                    if ($validator->fails()) {
                        return false;
                    }
                }

                return true;
            }
        );

        /*
         * Add validation for valid and existing files on the filesystem
         */
        /** @noinspection PhpUnusedParameterInspection */
        Validator::extend(
            'file',
            function ($attribute, $value, $parameters, $validator) {

                if (!is_file($value)) {
                    return false;
                }

                if (filesize($value) < 8) {
                    return false;
                }

                return true;

            }
        );

        /*
         * Add validation for abuse class
         */
        /** @noinspection PhpUnusedParameterInspection */
        Validator::extend(
            'abuseclass',
            function ($attribute, $value, $parameters, $validator) {
                $classes = Lang::get('classifications');
                return array_key_exists($value, $classes);
            }
        );

        /*
         * Add validation for abuse type
         */
        /** @noinspection PhpUnusedParameterInspection */
        Validator::extend(
            'abusetype',
            function ($attribute, $value, $parameters, $validator) {

                $types = config('types.type');
                return in_array($value, $types);
            }
        );

        /*
         * Add validation for string or boolean
         */
        /** @noinspection PhpUnusedParameterInspection */
        Validator::extend(
            'stringorboolean',
            function ($attribute, $value, $parameters, $validator) {

                foreach (['string', 'boolean'] as $validation) {

                    $validator = Validator::make(
                        [ 'field' => $value ],
                        [ 'field' => "required|{$validation}" ]
                    );

                    if (!$validator->fails()) {
                        return true;
                    }
                }

                return false;

            }
        );

        /*
         * Add validation for domain
         */
        /** @noinspection PhpUnusedParameterInspection */
        Validator::extend(
            'domain',
            function ($attribute, $value, $parameters, $validator) {

                if (is_bool($value)) {
                    return true;
                }

                if (!preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $value)
                    || !preg_match("/^.{1,253}$/", $value)
                    || !preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $value)
                ) {
                    return false;
                }

                if (!filter_var(
                    'http://' . $value,
                    FILTER_VALIDATE_URL
                ) === false) {
                    return true;
                }

                return true;

            }
        );

        /*
         * Add validation for URI
         */
        /** @noinspection PhpUnusedParameterInspection */
        Validator::extend(
            'uri',
            function ($attribute, $value, $parameters, $validator) {

                if (is_bool($value)) {
                    return true;
                }

                if (!filter_var(
                    'http://test.for.var.com' . $value,
                    FILTER_VALIDATE_URL
                ) === false) {
                    return true;
                }

                return false;

            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
