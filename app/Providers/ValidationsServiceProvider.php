<?php namespace AbuseIO\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

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
