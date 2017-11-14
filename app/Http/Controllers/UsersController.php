<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\UserFormRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\Role;
use AbuseIO\Models\User;
use AbuseIO\Traits\Api;
use AbuseIO\Transformers\UserTransformer;
use Config;
use Illuminate\Http\Request;
use Input;
use JavaScript;
use League\Fractal\Manager;
use Redirect;

/**
 * Class UsersController.
 */
class UsersController extends Controller
{
    /*
     * Load Traits
     */
    use Api;

    /**
     * @var User
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
     * @param User    $user
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
                    'apiShow',
                ],
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
        Javascript::put([
            't_disabled'  => uctrans('misc.disabled'),
            't_enabled'   => uctrans('misc.enabled'),
            't_none'      => uctrans('misc.none'),
            't_usersaved' => trans('users.user'),
        ]);

        // Create locales array
        $locales = ['null' => null];
        foreach (Config::get('app.locales') as $locale => $locale_data) {
            $locales[$locale] = $locale_data[0];
        }

        // Get saved search values.
        $searchValues = User::getSearchValues('users.search');

        // Only merge if there are saved values.
        if (is_array($searchValues)) {
            $this->searchFields = array_merge($this->searchFields, $searchValues);
        }

        $userList = $this->searchUsers();

        $accounts = Account::all()->pluck('name', 'id')->sort()->prepend(null, 'null');

        return view('users.index', [
            'users'             => $userList,
            'locale_selection'  => $locales,
            'locale_selected'   => null,
            'account_selection' => $accounts,
            'account_selected'  => null,
            'roles_selection'   => Role::all()->pluck('name', 'id')->sort(),
            'roles_selected'    => null,
            'disabled_checked'  => false,
            'auth_user'         => $this->auth_user,
            'search_options'    => $this->searchFields,
        ]);
    }

    /**
     * Get User.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function get(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update User.
     *
     * @param UserFormRequest $userForm
     * @param User            $user
     *
     * @return JsonResponse
     */
    public function update(UserFormRequest $userForm, User $user)
    {
        $formFields = $userForm->all();

        \Log::debug($user);
        if (empty($formFields['password'])) {
            unset($formFields['password']);
        }

        if (!array_key_exists('roles', $formFields)) {
            $formFields['roles'] = [];
        }

        $user->roles()->sync($formFields['roles']);
        $user->update($formFields);
        $user = $user->fresh();

        return response()->json(['user' => $user, 'message' => trans('users.message.updated', ['user' => $user->fullName()])]);
    }

    /**
     * Enable User.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function enable(User $user)
    {
        if (!$this->user->mayEnable($this->auth_user)) {
            return response()->json(['message' => trans('users.message.no_self_action', ['action' => trans('misc.enable')])]);
        }

        $user->disabled = false;
        $user->save();

        return response()->json(['user' => $user, 'message' => trans('users.message.enabled', ['user' => $user->fullName()])]);
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
        if (!$this->user->mayEnable($this->auth_user)) {
            return response()->json(['message' => trans('users.message.no_self_action', ['action' => trans('misc.disabled')])]);
        }

        $user->disabled = true;
        $user->save();

        return response()->json(['user' => $user, 'message' => trans('users.message.disabled', ['user' => $user->fullName()])]);
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
                       ->with('message', trans('users.message.created', ['user' => $user->fullName()]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        // Do not allow our own user to be destroyed.
        if ($user->is($this->auth_user)) {
            return response()->json(['sucess' => false, 'message' => trans('users.message.no_self_action', ['action' => trans('misc.delete')])]);
        }

        // Save the username, so we can show it in the snackbar.
        $userName = $user->fullName();

        $user->delete();

        return response()->json(['success' => true, 'message' => trans('users.message.deleted', ['user' => $userName])]);
    }

    /**
     * Return all users for API request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        return $this->respondWithCollection(User::all(), new UserTransformer());
    }

    /**
     * Fetch a single user for API request.
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
        $sort = (empty(Input::get('sort')) || is_null(Input::get('sort'))) ? 'asc' : Input::get('sort');

        $this->user = $this->user->orderBy($field, $sort);

        if (\Auth::user()->account->isSystemAccount()) {
            return $this->user->paginate(15);
        } else {
            return $this->user->where('account_id', '=', \Auth::user()->account->id)->paginate(15);
        }
    }
}
