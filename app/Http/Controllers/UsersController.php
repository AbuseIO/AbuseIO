<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\UserFormRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\Role;
use AbuseIO\Models\User;
use Config;
use Log;
use yajra\Datatables\Datatables;
use Redirect;
use Form;

/**
 * Class UsersController
 * @package AbuseIO\Http\Controllers
 */
class UsersController extends Controller
{

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // is the logged in account allowed to execute an action on the User
        $this->middleware('checkaccount:User', ['except' => ['search', 'index', 'create', 'store', 'export']]);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $auth_account = $this->auth_user->account;

        $users = User::select('users.*', 'accounts.name as account_name')
            ->leftJoin('accounts', 'accounts.id', '=', 'users.account_id');

        if (!$auth_account->isSystemAccount()) {
            $users = $users->where('accounts.id', '=', $auth_account->id);
        }

        return Datatables::of($users)
            ->addColumn(
                'actions',
                function ($user) {
                    $actions = Form::open(
                        [
                            'route' => ['admin.users.destroy', $user->id],
                            'method' => 'DELETE',
                            'class' => 'form-inline'
                        ]
                    );
                    $actions .= ' <a href="users/' . $user->id .
                        '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> '.
                        trans('misc.button.show').'</a> ';
                    $actions .= ' <a href="users/' . $user->id .
                        '/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> '.
                        trans('misc.button.edit').'</a> ';
                    if ($user->disabled) {
                        $actions .= ' <a href="users/' . $user->id .
                            '/enable" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-ok-circle"></i> '.
                            trans('misc.button.enable').'</a> ';
                    } else {
                        $actions .= ' <a href="users/' . $user->id .
                            '/disable" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-ban-circle"></i> '.
                            trans('misc.button.disable').'</a> ';
                    }
                    $disabled = ($user->id == 1) ? ' disabled' : '';

                    $actions .= Form::button(
                        '<i class="glyphicon glyphicon-remove"></i> '. trans('misc.button.delete'),
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-xs'. $disabled
                        ]
                    );
                    $actions .= Form::close();
                    return $actions;
                }
            )
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10);

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
        $roles = Role::lists('name', 'id');

        $locales = array();
        foreach (Config::get('app.locales') as $locale => $locale_data) {
            $locales[$locale] = $locale_data[0];
        }

        return view('users.create')
            ->with('account_selection', $accounts)
            ->with('selected', null)
            ->with('locale_selection', $locales)
            ->with('locale_selected', null)
            ->with('disabled_checked', 0)
            ->with('roles', $roles)
            ->with('selected_roles', null)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserFormRequest $userForm
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $userForm)
    {
        $userData = $userForm->all();

        // update data for the create method
        if (gettype($userData['disabled']) == 'string') {
            $userData['disabled'] = ($userData['disabled'] == 'true');
        }

        $user = User::create($userData);

        // link the roles to the user
        $roles = $userForm->get('roles');

        if ($roles != null) {
            $user->roles()->sync($roles);
        }

        return Redirect::route('admin.users.index')
            ->with('message', 'User has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  User   $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $account = Account::find($user->account_id);

        $locale = Config::get('app.locales');

        return view('users.show')
            ->with('account', $account)
            ->with('user', $user)
            ->with('roles', $user->roles)
            ->with('language', $locale[$user->locale][0])
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User   $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $accounts = Account::lists('name', 'id');
        $roles = Role::lists('name', 'id');

        $locales = array();
        foreach (Config::get('app.locales') as $locale => $locale_data) {
            $locales[$locale] = $locale_data[0];
        }

        return view('users.edit')
            ->with('user', $user)
            ->with('account_selection', $accounts)
            ->with('selected', $user->account_id)
            ->with('locale_selection', $locales)
            ->with('locale_selected', null)
            ->with('disabled_checked', $user->disabled)
            ->with('roles', $roles)
            ->with('selected_roles', $user->roles->lists('id')->toArray())
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserFormRequest $userForm
     * @param  User            $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $userForm, User $user)
    {
        $userData = $userForm->all();

        // massage data
        if (gettype($userData['disabled']) == 'string') {
            $userData['disabled'] = ($userData['disabled'] == 'true');
        }

        Log::info($userData);

        if (empty($userData['password'])) {
            unset($userData['password']);
        }

        // update the user with the data
        $user->update($userData);

        // link the roles to the user
        $roles = $userForm->get('roles');
        if ($roles == null) {
            $roles = [];
        }
        $user->roles()->sync($roles);

        return Redirect::route('admin.users.show', $user->id)
            ->with('message', 'User has been updated.');
    }

    /**
     * Enable the user
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable(User $user)
    {
        if (!$user->mayEnable($this->auth_user)) {
            return Redirect::route('admin.users.index')
                ->with('message', 'User is not authorized to enable user "'. $user->fullName() . '"');
        }

        $user->disabled = false;
        $user->save();

        return Redirect::route('admin.users.index')
            ->with('message', 'User "'. $user->fullName() . '" has been enabled');
    }

    /**
     * Disable the user
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable(User $user)
    {
        if (!$user->mayDisable($this->auth_user)) {
            return Redirect::route('admin.users.index')
                ->with('message', 'User is not authorized to disable user "'. $user->fullName() . '"');
        }

        $user->disabled = true;
        $user->save();

        return Redirect::route('admin.users.index')
            ->with('message', 'User "'. $user->fullName() . '" has been disabled');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User   $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Do not allow our own user to be destroyed.
        if ($user->id == $this->auth_user->id) {
            return Redirect::back()
                ->with('message', 'Not allowed to delete current.');
        }

        $user->delete();

        return Redirect::route('admin.users.index')
            ->with('message', 'User has been deleted.');
    }
}
