<?php

namespace AbuseIO\Http\Controllers;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(
            'home',
            [
                'auth_user' => $this->auth_user,
                'version'   => config('app.version'),
                'update'    => link_to_route('admin.version', trans('misc.versioncheck')),
            ]
        );
    }

    public function version()
    {
        $url = 'https://abuse.io/version.json';

        $curl_options = [
            CURLOPT_URL             => $url,
            CURLOPT_TIMEOUT         => 5,
            CURLOPT_RETURNTRANSFER  => 1,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);
        if (($contents = curl_exec($ch)) === false) {
            // Failed to fetch the file.
            $message = trans('misc.error');
        } else {
            // Fetched file, check if it's valid json
            if (gettype(json_decode(trim($contents))) == 'object') {
                // Valid json data
                $info = json_decode(trim($contents));
                if (version_compare($info->version, config('app.version'), '>')) {
                    $message = trans('misc.available').": <a href=\"{$info->url}\">{$info->version}</a>";
                } else {
                    $message = trans('misc.noupdate');
                }
            } else {
                // No (valid) json data
                $message = trans('misc.error');
            }
        }
        curl_close($ch);

        return view(
            'home',
            [
                'auth_user' => $this->auth_user,
                'version'   => config('app.version'),
                'update'    => "{$message}",
            ]
        );
    }
}
