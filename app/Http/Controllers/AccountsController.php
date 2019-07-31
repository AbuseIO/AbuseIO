<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\AccountFormRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use AbuseIO\Traits\Api;
use AbuseIO\Transformers\AccountTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Redirect;
use yajra\Datatables\Datatables;

/**
 * Class AccountsController.
 */
class AccountsController extends Controller
{
    use Api;

    /**
     * AccountsController constructor.
     *
     * @param Manager $fractal
     * @param Request $request
     */
    public function __construct(Manager $fractal, Request $request)
    {
        parent::__construct();

        // initialize the Api methods
        $this->apiInit($fractal, $request);

        // is the logged in account allowed to execute an action on the account
        $this->middleware(
            'checkaccount:Account',
            ['except' => ['search', 'index', 'create', 'store', 'export', 'logo', 'apiIndex', 'apiStore', 'apiShow', 'apiUpdate', 'apiDestroy']]
        );

        // method that only may be executed by the systemaccount
        $this->middleware('checksystemaccount', ['only' => ['create', 'store']]);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $accounts = Account::all();

        return $this->respondWithCollection($accounts, new AccountTransformer());
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
                                $account->id,
                            ],
                            'method' => 'DELETE',
                            'class'  => 'form-inline',
                        ]
                    );
                    $actions .= ' <a href="accounts/'.$account->id.
                        '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-eye-open"></span> '.
                        trans('misc.button.show').'</a> ';
                    $actions .= ' <a href="accounts/'.$account->id.
                        '/edit" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-edit"></span> '.
                        trans('misc.button.edit').'</a> ';
                    if ($account->disabled) {
                        $actions .= ' <a href="accounts/'.$account->id.
                            '/enable'.
                            '" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok-circle"></span> '.
                            trans('misc.button.enable')
                            .'</a> ';
                    } else {
                        $actions .= ' <a href="accounts/'.$account->id.
                            '/disable'.
                            '" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> '.
                            trans('misc.button.disable')
                            .'</a> ';
                    }
                    $actions .= \Form::button(
                        '<i class="glyphicon glyphicon-remove"></i> '
                        .trans('misc.button.delete'),
                        [
                            'type'  => 'submit',
                            'class' => 'btn btn-danger btn-xs',
                        ]
                    );
                    $actions .= \Form::close();

                    return $actions;
                }
            )
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::pluck('name', 'id');

        return view('accounts.create')
            ->with('brand_selection', $brands)
            ->with('selected', null)
            ->with('disabled_checked', 0)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AccountFormRequest $accountForm
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AccountFormRequest $accountForm)
    {
        $accountData = $accountForm->all();

        // massage data
        if (gettype($accountData['disabled']) == 'string') {
            $accountData['disabled'] = ($accountData['disabled'] == 'true');
        }

        Account::create($accountForm->all());

        return Redirect::route('admin.accounts.index')
            ->with('message', 'Account has been created');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AccountFormRequest $accountForm
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apiStore(AccountFormRequest $accountForm)
    {
        $account = Account::create($accountForm->all());

        return  $this->respondWithItem($account, new AccountTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param Account $account
     *
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
     * @param Account $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiShow(Account $account)
    {
        return $this->respondWithItem($account, new AccountTransformer());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Account $account
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        // may we edit this brand (is the brand connected to our account)
        if (!$account->mayEdit($this->auth_user)) {
            return Redirect::route('admin.accounts.show', $account->id)
                ->with('message', trans('accounts.no_edit_permissions'));
        }

        $brands = Brand::pluck('name', 'id');

        return view('accounts.edit')
            ->with('account', $account)
            ->with('brand_selection', $brands)
            ->with('disabled_checked', $account->disabled)
            ->with('selected', $account->brand_id)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AccountFormRequest $accountForm FormRequest
     * @param Account            $account     Account
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AccountFormRequest $accountForm, Account $account)
    {
        $accountData = $accountForm->all();

        // may we edit this account
        if (!$account->mayEdit($this->auth_user)) {
            return Redirect::back()
                ->with('message', trans('accounts.no_edit_permissions'));
        }

        // massage data
        if (gettype($accountData['disabled']) == 'string') {
            $accountData['disabled'] = ($accountData['disabled'] == 'true');
        }

        // may we disable the account, when requested
        if ($account->isSystemAccount() && $accountData['disabled']) {
            return Redirect::back()
                ->with('message', trans('accounts.cannot_disable_sys_acc'));
        }

        $account->update($accountData);

        return Redirect::route('admin.accounts.show', $account->id)
            ->with('message', trans('accounts.account_updated'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AccountFormRequest $accountForm
     * @param Account            $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(AccountFormRequest $accountForm, Account $account)
    {
        $account->update($accountForm->all());

        return $this->respondWithItem($account, new AccountTransformer());
    }

    /**
     * Disable the account.
     *
     * @param Account $account
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable(Account $account)
    {
        if (!$account->mayDisable($this->auth_user)) {
            return Redirect::route('admin.accounts.index')
                ->with('message', trans('accounts.no_disable_permissions').' '.$account->name.'"');
        }

        $account->disabled = true;
        $account->save();

        return Redirect::route('admin.accounts.index')
            ->with('message', 'Account "'.$account->name.'" has been disabled');
    }

    /**
     * Enable the account.
     *
     * @param Account $account
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable(Account $account)
    {
        if (!$account->mayEnable($this->auth_user)) {
            return Redirect::route('admin.accounts.index')
                ->with('message', trans('accounts.no_enable_permissions').' '.$account->name.'"');
        }

        $account->disabled = false;
        $account->save();

        return Redirect::route('admin.accounts.index')
            ->with('message', 'Account "'.$account->name.'" has been enabled');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Account $account
     *
     * @return \\Illuminate\Http\RedirectResponse
     */
    public function destroy(Account $account)
    {
        $brand = $account->brand;

        if (!$account->mayDestroy($this->auth_user)) {
            return Redirect::route('admin.accounts.index')
                ->with('message', trans('accounts.no_edit_permissions'));
        }

        // Do not allow the system admin user account to be deleted.
        if ($account->isSystemAccount()) {
            return Redirect::back()
                ->with('message', 'Not allowed to delete the default admin account.');
        }

        // delete the linked users
        foreach ($account->users as $user) {
            $user->delete();
        }

        // delete the account
        $account->delete();

        // delete the brand
        if ($brand->canDelete()) {
            $brand->delete();
        }

        return Redirect::route('admin.accounts.index')
            ->with('message', 'Account and it\'s related users and brands have been deleted.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Account $account
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy(Account $account)
    {
        $brand = $account->brand;

        if (!$account->mayDestroy($this->auth_user)) {
            return $this->errorUnauthorized();
        }

        // Do not allow the system admin user account to be deleted.
        if ($account->isSystemAccount()) {
            return $this->errorForbidden('Can delete the systemaccount');
        }

        // delete the linked users
        foreach ($account->users as $user) {
            $user->delete();
        }

        // delete the account
        $account->delete();

        // delete the brand
        if ($brand->canDelete()) {
            $brand->delete();
        }

        return $this->respondWithItem($account, new AccountTransformer());
    }
}
