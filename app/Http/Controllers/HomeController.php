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
        return view('home', ['auth_user' => $this->auth_user]);
    }

    /**
     * Checks the version of this installation and reports newer version.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function version()
    {
        $response = [
            'statusCode' => 200,
            'version'    => config('app.version'),
            'status'     => session()->get('updated_status'),
            'action'     => 'nothing',
        ];

        if (session()->has('updated_status')) {
            $response['body'] = trans('misc.'.$response['status']);

            return response()->json($response);
        }

        $url = 'https://abuse.io/version.json';

        $curl_options = [
            CURLOPT_URL            => $url,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_RETURNTRANSFER => 1,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);
        if (($contents = curl_exec($ch)) === false) {
            // Failed to fetch the file.
            $response['error'] = trans('misc.error');
            $response['statusCode'] = 500;
        } else {
            // Fetched file, check if it's valid json
            if (gettype(json_decode(trim($contents))) == 'object') {
                // Valid json data
                $info = json_decode(trim($contents));
                if (version_compare($info->version, config('app.version'), '>')) {
                    $response['status'] = 'available';
                    $response['body'] = trans('misc.available');
                    $response['action'] = 'showDialog';
                } else {
                    $response['status'] = 'noupdate';
                    $response['body'] = trans('misc.noupdate');
                }
            } else {
                // No (valid) json data
                $response['error'] = trans('misc.error');
                $response['statusCode'] = 500;
            }
        }
        curl_close($ch);

        session()->put('updated_status', $response['status']);

        return response()->json($response)->setStatusCode($response['statusCode']);
    }
}
