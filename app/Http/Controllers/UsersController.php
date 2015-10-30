<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\UserFormRequest;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use Redirect;
use Input;
use Hash;

class UsersController extends Controller
{
    /*
     * Call the parent constructor to generate a base ACL
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10); // ::with('user')

        return view('users.index')
            ->with('users', $users)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::lists('name', 'id');

        return view('users.create')
            ->with('account_selection', $accounts)
            ->with('selected', null)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Store a newly created resource in storage.
     * @param  UserFormRequest $user
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $user)
    {
        $input = Input::all();
        User::create($input);

        return Redirect::route('admin.users.index')
            ->with('message', 'User has been created');
    }

    /**
     * Display the specified resource.
     * @param  User   $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $account = Account::find($user->account_id);

        return view('users.show')
            ->with('account', $account)
            ->with('user', $user)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for editing the specified resource.
     * @param  User   $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $accounts = Account::lists('name', 'id');

        return view('users.edit')
            ->with('user', $user)
            ->with('account_selection', $accounts)
            ->with('selected', $user->account_id)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     * @param  UserFormRequest $request
     * @param  User            $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, User $user)
    {
        $input = array_except(Input::all(), '_method');

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $user->update($input);

        return Redirect::route('admin.users.show', $user->id)
            ->with('message', 'User has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     * @param  User   $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Do not allow the default admin user account to be deleted.
        if ($user->id == 1) {
            return Redirect::back()
                ->with('message', 'Not allowed to delete the default admin user.');
        }

        $user->delete();

        return Redirect::route('admin.users.index')
            ->with('message', 'User has been deleted.');
    }
}
