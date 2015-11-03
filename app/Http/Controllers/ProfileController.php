<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\ProfileFormRequest;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\User;
use Input;
use Redirect;
use Hash;

class ProfileController extends Controller
{
    /*
     * Call the parent constructor to generate a base ACL
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('profile.edit')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileFormRequest $request)
    {
        $input = array_except(Input::all(), '_method');

        $data = [
            "first_name" => $input['first_name'],
            "last_name" => $input['last_name'],
            "email" => $input['email']
        ];

        if (!empty($input['password'])) {
            $data['password'] = Hash::make($input['password']);
        }

        try {
            $this->auth_user->update($data);

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $message = 'Unknown error code: ' . $errorCode;

            if ($errorCode === 1062) {
                $message = 'You cannot use this e-mail address.';
            }

            return Redirect::back()
                ->with('message', $message);
        }

        return Redirect::route('admin.profile.index')
            ->with('message', 'Profile has been updated.');
    }

}
