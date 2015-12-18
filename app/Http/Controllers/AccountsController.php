<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\AccountFormRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use yajra\Datatables\Datatables;
use Redirect;

class AccountsController extends Controller
{

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
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $auth_account = $this->auth_user->account;

        //return all accounts when we are in the system account
        //in a normal account only show the current linked one

        if ($auth_account->isSystemAccount()) {
            $accounts = Account::all();
        } else {
            // retrieve the account as a collection
            $accounts = Account::where('id', '=', $auth_account->id)->get();
        }

        return Datatables::of($accounts)
            ->addColumn(
                'actions',
                function ($account) {
                    $actions = \Form::open(
                        [
                            'route' => [
                                'admin.accounts.destroy',
                                $account->id
                            ],
                            'method' => 'DELETE',
                            'class' => 'form-inline'
                        ]
                    );
                    $actions .= ' <a href="accounts/' . $account->id .
                        '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-eye-open"></span> '.
                        trans('misc.button.show').'</a> ';
                    $actions .= ' <a href="accounts/' . $account->id .
                        '/edit" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-edit"></span> '.
                        trans('misc.button.edit').'</a> ';
                    if ($account->disabled) {
                        $actions .= ' <a href="accounts/' . $account->id .
                            '/enable'.
                            '" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok-circle"></span> '.
                            trans('misc.button.enable')
                            .'</a> ';
                    } else {
                        $actions .= ' <a href="accounts/' . $account->id .
                            '/disable'.
                            '" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> '.
                            trans('misc.button.disable')
                            .'</a> ';
                    }
                    $actions .= \Form::button(
                        '<i class="glyphicon glyphicon-remove"></i> '
                        . trans('misc.button.delete'),
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-xs'
                        ]
                    );
                    $actions .= \Form::close();
                    return $actions;
                }
            )
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::lists('name', 'id');

        return view('accounts.create')
            ->with('brand_selection', $brands)
            ->with('selected', null)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountFormRequest $accountForm)
    {
        Account::create($accountForm->all());

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
        // may we edit this brand (is the brand connected to our account)
        if (!$account->mayEdit($this->auth_user)) {
            return Redirect::route('admin.accounts.show', $account->id)
                ->with('message', 'User is not authorized to edit this account.');
        }

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
     * @param  AccountFormRequest $request FormRequest
     * @param  Account            $account Account
     * @return \Illuminate\Http\Response
     */
    public function update(AccountFormRequest $accountForm, Account $account)
    {
        // may we edit this brand (is the brand connected to our account)
        if (!$account->mayEdit($this->auth_user)) {
            return Redirect::route('admin.accounts.show', $account->id)
                ->with('message', 'User is not authorized to edit this account.');
        }

        $account->update($accountForm->all());

        return Redirect::route('admin.accounts.show', $account->id)
            ->with('message', 'Account has been updated.');
    }


    /**
     * Disable the account
     *
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable(Account $account)
    {
        if (!$account->mayDisable($this->auth_user)) {
            return Redirect::route('admin.accounts.index')
                ->with('message', 'User is not authorized to disable account "'. $account->name . '"');
        }

        $account->disabled = true;
        $account->save();

        return Redirect::route('admin.accounts.index')
            ->with('message', 'Account "'. $account->name . '" has been disabled');
    }

    /**
     * Enable the account
     *
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable(Account $account)
    {
        if (!$account->mayEnable($this->auth_user)) {
            return Redirect::route('admin.accounts.index')
                ->with('message', 'User is not authorized to enable account "'. $account->name . '"');
        }

        $account->disabled = false;
        $account->save();

        return Redirect::route('admin.accounts.index')
            ->with('message', 'Account "'. $account->name . '" has been enabled');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        // may we edit this brand (is the brand connected to our account)
        if (!$account->mayDestroy($this->auth_user)) {
            return Redirect::route('admin.accounts.index')
                ->with('message', 'User is not authorized to edit this account.');
        }


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
