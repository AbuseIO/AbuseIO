<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\AccountFormRequest;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use AbuseIO\Models\User;
use Redirect;
use Input;

class AccountsController extends Controller
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
        $accounts = Account::paginate(10); // ::with('user')

        return view('accounts.index')
            ->with('accounts', $accounts)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounts.create')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountFormRequest $account)
    {
        $input = Input::all();

        try {
            Account::create($input);

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $message = 'Unknown error code: ' . $errorCode;

            if ($errorCode === 1062) {
                $message = 'Another account with this name already exists';
            }

            return Redirect::back()
                ->with('message', $message);
        }

        return Redirect::route('admin.accounts.index')
            ->with('message', 'Account has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        $brand = Brand::find($account->brand_id);

        return view('accounts.show')
            ->with('account', $account)
            ->with('brand', $brand)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        $brands = Brand::lists('name', 'id');

        return view('accounts.edit')
            ->with('account', $account)
            ->with('brand_selection', $brands)
            ->with('selected', $account->brand_id)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     * @param  AccountFormRequest $request FormRequest
     * @param  Account            $account Account
     * @return \Illuminate\Http\Response
     */
    public function update(AccountFormRequest $request, Account $account)
    {
        $input = array_except(Input::all(), '_method');

        try {
            $account->update($input);

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $message = 'Unknown error code: ' . $errorCode;

            if ($errorCode === 1062) {
                $message = 'Another account with this name already exists';
            }

            return Redirect::back()
                ->with('message', $message);
        }

        return Redirect::route('admin.accounts.show', $account->id)
            ->with('message', 'Account has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        // Do not allow the default admin user account to be deleted.
        if ($account->id == 1) {
            return Redirect::back()
                ->with('message', 'Not allowed to delete the default admin account.');
        }

        $account->delete();
        // todo: delete related users/brands as well

        return Redirect::route('admin.accounts.index')
            ->with('message', 'Account and it\'s related users and brands have been deleted.');
    }
}
