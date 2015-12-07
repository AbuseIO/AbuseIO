<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\UserFormRequest;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use yajra\Datatables\Datatables;
use Redirect;
use Input;
use Form;

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
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $users = User::select('users.*', 'accounts.name as account_name')
            ->leftJoin('accounts', 'accounts.id', '=', 'users.account_id');

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
                    $actions .= Form::button(
                        '<i class="glyphicon glyphicon-remove"></i> '. trans('misc.button.delete'),
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-xs'
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
        $user->update($input);

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
