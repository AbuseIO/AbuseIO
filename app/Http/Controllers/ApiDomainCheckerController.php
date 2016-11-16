<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Sabre\Uri as Uri;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Controllers\Controller;

class ApiDomainCheckerController extends Controller
{

   

    /**
     * check an external domain if it is a valid abuseIO domain
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = Uri\normalize(
            Uri\resolve($request->url, '/apiversion/')
        );

        try {
            if ($response = file_get_contents($url)) {
                return response()->json(['data' => $response]);
            }
        } catch (\ErrorException $e) {}

        return response()->json(['error' => 'couldn\'t resolve domain'])->setStatusCode(422);
    }
}


