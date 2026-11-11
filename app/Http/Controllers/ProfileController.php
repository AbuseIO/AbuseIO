<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\UpdateProfileRequest;
use Redirect;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * ProfileController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProfileRequest $profileForm
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $profileForm)
    {
        $input = $profileForm->all();

        $data = [
            'first_name' => $input['first_name'],
            'last_name'  => $input['last_name'],
            'email'      => $input['email'],
        ];

        if (!empty($input['password'])) {
            $data['password'] = $input['password'];
        }

        try {
            $this->auth_user->update($data);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $message = 'Unknown error code: '.$errorCode;

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
