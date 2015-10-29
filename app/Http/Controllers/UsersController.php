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
        parent::__construct('createDynamicACL');
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
            ->with('user', $this->user);
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
            ->with('user', $this->user);
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
     * @param  User   $luser
     * @return \Illuminate\Http\Response
     */
    public function show(User $luser)
    {
        $account = Account::find($luser->account_id);

        return view('users.show')
            ->with('account', $account)
            ->with('luser', $luser)
            ->with('user', $this->user);
    }

    /**
     * Show the form for editing the specified resource.
     * @param  User   $luser
     * @return \Illuminate\Http\Response
     */
    public function edit(User $luser)
    {
        $accounts = Account::lists('name', 'id');

        return view('users.edit')
            ->with('luser', $luser)
            ->with('account_selection', $accounts)
            ->with('selected', $luser->account_id)
            ->with('user', $this->user);
    }

    /**
     * Update the specified resource in storage.
     * @param  UserFormRequest $request
     * @param  User            $luser
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, User $luser)
    {
        $input = array_except(Input::all(), '_method');

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $luser->update($input);

        return Redirect::route('admin.users.show', $luser->id)
            ->with('message', 'User has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     * @param  User   $luser
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $luser)
    {
        // Do not allow the default admin user account to be deleted.
        if ($luser->id == 1) {
            return Redirect::back()
                ->with('message', 'Not allowed to delete the default admin user.');
        }

        $luser->delete();

        return Redirect::route('admin.users.index')
            ->with('message', 'User has been deleted.');
    }
}
