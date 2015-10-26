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

class ProfilesController extends Controller
{
    /*
     * Call the parent constructor to generate a base ACL
     */
    public function __construct()
    {
        parent::__construct('createDynamicACL');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.index')
            ->with('user', $this->user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        if (!empty($input['password']))
            $data['password'] = Hash::make($input['password']);

        $this->user->update($data);

        return Redirect::route('admin.profile.index')
            ->with('message', 'Profile has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
