<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\UserFormRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use AbuseIO\Models\Role;
use AbuseIO\Traits\Api;
use AbuseIO\Transformers\UserTransformer;
use Config;
use Illuminate\Http\Request;
use Input;
use League\Fractal\Manager;
use Redirect;

/**
 * Class UsersController.
 */
class UsersController extends Controller
{
    /**
     * Load Traits
     */
    use Api;

    /**
     * @var User $user
     */
    private $user;

    /**
     * @var array
     */
    public $searchFields = [
        'id'         => null,
        'first_name' => null,
        'last_name'  => null,
        'account_id' => -1,
    ];

    /**
     * UsersController constructor.
     *
     * @param User $user
     * @param Manager $fractal
     * @param Request $request
     */
    public function __construct(User $user, Manager $fractal, Request $request)
    {
        parent::__construct();

        $this->user = $user;

        // Initialize the API methods.
        $this->apiInit($fractal, $request);

        // Is the logged in account allowed to execute an action in this controller.
        $this->middleware(
            'checkaccount:User',
            [
                'except' => [
                    'search',
                    'index',
                    'create',
                    'store',
                    'export',
                    'apiIndex',
                    'apiShow'
                ]
            ]
        );
    }

    /**
     * Display list of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get saved search values.
        $searchValues = User::getSearchValues('users.search');

        // Only merge if there are saved values.
        if (is_array($searchValues)) {
            $this->searchFields = array_merge($this->searchFields, $searchValues);
        }

        $userList = $this->searchUsers();

        return view('users.index', [
            'users'          => $userList,
            'auth_user'      => $this->auth_user,
            'search_options' => $this->searchFields,
            'accounts'       => Account::all()->pluck('name', 'id')->put(-1, 'All accounts')->sort(),
        ]);
    }

    /**
     * Search for users.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        // Give the possible search fields a value and don't save _token and _method
        $this->searchFields = array_merge($this->searchFields, Input::except(['_token', '_method']));

        // Save the search values
        User::saveSearchValues('users.search', $this->searchFields);

        $userList = $this->searchUsers();

        return view('users.index', [
            'users'          => $userList,
            'auth_user'      => $this->auth_user,
            'search_options' => $this->searchFields,
            'accounts'       => Account::all()->pluck('name', 'id')->put(-1, 'All accounts')->sort(),
        ]);
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $data    = $this->handleShow($user);
        $user    = $data['user'];
        $account = $data['account'];

        $locale = Config::get('app.locales');

        return view('users.show')
            ->with('account', $account)
            ->with('user', $user)
            ->with('roles', $user->roles)
            ->with('language', $locale[$user->locale][0])
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locales = [];
        foreach (Config::get('app.locales') as $locale => $localeData) {
            $locales[$locale] = $localeData[0];
        }

        return view('users.create')
            ->with('account_selection', Account::lists('name', 'id')->sort())
            ->with('selected', null)
            ->with('locale_selection', $locales)
            ->with('locale_selected', null)
            ->with('disabled_checked', 0)
            ->with('roles', Role::lists('name', 'id')->sort())
            ->with('selected_roles', null)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $locales = [];
        foreach (Config::get('app.locales') as $locale => $locale_data) {
            $locales[$locale] = $locale_data[0];
        }

        return view('users.edit')
            ->with('user', $user)
            ->with('account_selection', Account::lists('name', 'id')->sort())
            ->with('selected', $user->account_id)
            ->with('locale_selection', $locales)
            ->with('locale_selected', null)
            ->with('disabled_checked', $user->disabled)
            ->with('roles', Role::lists('name', 'id')->sort())
            ->with('selected_roles', $user->roles->lists('id')->toArray())
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserFormRequest $userForm
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserFormRequest $userForm)
    {
        $formFields = $userForm->all();

        // Convert string to boolean.
        $formFields['disabled'] = ($formFields['disabled'] === 'true');

        $user = User::create($formFields);

        // Link the roles to the user.
        if ($formFields['roles'] != null) {
            $user->roles()->sync($formFields['roles']);
        }

        return Redirect::route('admin.users.show', $user->id)
                       ->with('message', 'User "'. $user->fullName() .'" has been created.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserFormRequest $userForm
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserFormRequest $userForm, User $user)
    {
        $formFields = $userForm->all();

        // Convert string to boolean
        $formFields['disabled'] = ($formFields['disabled'] === 'true');

        if (empty($formFields['password'])) {
            unset($formFields['password']);
        }

        // update the user with the data
        $user->update($formFields);

        // link the roles to the user
        if ($formFields['roles'] == null) {
            $formFields['roles'] = [];
        }
        $user->roles()->sync($formFields['roles']);

        return Redirect::route('admin.users.show', $user->id)
                       ->with('message', 'User "'. $user->fullName() .'" has been updated.');
    }

    /**
     * Enable the user.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable(User $user)
    {
        if ( ! $this->user->mayEnable($this->auth_user)) {
            return back()->with('message', 'User is not authorized to enable user "' . $user->fullName() . '"');
        }

        $user->disabled = false;
        $user->save();

        return back()->with('message', 'User "' . $user->fullName() . '" has been enabled.');
    }

    /**
     * Disable the user.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable(User $user)
    {
        if ( ! $this->user->mayEnable($this->auth_user)) {
            return back()->with('message', 'User is not authorized to disable user "' . $user->fullName() . '"');
        }

        $user->disabled = true;
        $user->save();

        return back()->with('message', 'User "' . $user->fullName() . '" has been disabled.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Do not allow our own user to be destroyed.
        if ($user->is($this->auth_user)) {
            return Redirect::back()->with('message', 'Not allowed to delete yourself.');
        }

        // Save, so we can show it in the snackbar.
        $userName = $user->fullName();

        $user->delete();

        return Redirect::route('admin.users.index')
                       ->with('message', 'User "'. $userName .'" has been deleted.');
    }

    /**
     * Return all users for API request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        return $this->respondWithCollection(User::all(), new UserTransformer());
    }

    /**
     * Fetch a single user for API request
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiShow(User $user)
    {
        $data = $this->handleShow($user);

        return $this->respondWithItem($data['user'], new UserTransformer());
    }

    /**
     * Retrieve the necessary data for the show and apiShow functions.
     *
     * @param User $user
     *
     * @return array
     */
    private function handleShow(User $user)
    {
        return [
            'user'    => $user,
            'account' => $user->account,
        ];
    }

    public function searchUsers()
    {
        // Search the users
        foreach ($this->searchFields as $name => $value) {
            if ($value != null && $value > -1) {
                // null = empty and -1 for select inputs that are set to default
                $this->user = $this->user->where($name, 'like', "%{$value}%");
            }
        }

        // Sort the list
        $field = (empty(Input::get('field')) || is_null(Input::get('field'))) ? 'id' : Input::get('field');
        $sort  = (empty(Input::get('sort')) || is_null(Input::get('sort'))) ? 'asc' : Input::get('sort');

        $this->user = $this->user->orderBy($field, $sort);

        if (\Auth::user()->account->isSystemAccount()) {
            return $this->user->paginate(15);
        } else {
            return $this->user->where('account_id', '=', \Auth::user()->account->id)->paginate(15);
        }
    }
}
