<?php

namespace AbuseIO\Http\Controllers;
use Illuminate\Http\Request;
use Log;
use File;
use Config;

/**
 * Class WhitelistController
 */
class WhitelistController extends Controller
{
    /**
     * Display a listing of the whitelisted IPs/subnets.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing()
    {
        $config_filepath = file_get_contents(config_path(env('APP_ENV') . '/whitelist.json'));
        $whitelist = json_decode($config_filepath, true);
        return view(
            'whitelist',
            [
                'auth_user' => $this->auth_user,
                'whitelist' => $whitelist,
            ]
        );
    }

    /**
     * Update the whitelisted IPs and Subnets
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $IPs = array();
        // POST parameters' names contain all submitted IPs/subnets
        foreach(array_keys($request->all()) as $IP){
            if(strcmp($IP, "_token") !== 0){    // Ignore the submitted CSRF token
                $IP = str_replace("_", ".", $IP); // PHP replaces dots with underscores
                $tokens = explode("/", $IP);

                // Validate the IP/Subnet before storing
                // The first part of the exploded string is an IP and the second
                // is the subnet mask, if there is one.
                if (filter_var($tokens[0], FILTER_VALIDATE_IP)){
                    if (sizeof($tokens) == 2 and in_array($tokens[1], range(8,32))){
                        $IPs[] = $IP;
                    }else if (sizeof($tokens) == 1){
                        $IPs[] = $IP;
                    }
                }
            }
        }

        /* Update the configuration file */
        $config_filepath = file_get_contents(config_path(env('APP_ENV') . '/whitelist.json'));
        if(file_put_contents($config_filepath, json_encode($IPs, JSON_PRETTY_PRINT))){
            return redirect('/admin/home')->with('message', trans('whitelist.successMsg'));
        }else{
            return redirect('/admin/home')->with('message', trans('whitelist.errorMsg'));
        }
    }
}
