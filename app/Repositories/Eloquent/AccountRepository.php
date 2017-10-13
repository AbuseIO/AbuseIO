<?php

namespace AbuseIO\Repositories\Eloquent;

use AbuseIO\Models\Account;
use AbuseIO\Repositories\AbstractRepository;
use AbuseIO\Repositories\Contracts\AccountRepositoryInterface;

/**
 * Class UserRepositoryInterface.
 */
class AccountRepository extends AbstractRepository implements AccountRepositoryInterface
{
    /**
     * @var Account Model
     */
    protected $model;

    /**
     * @var AccountRepositoryInterface
     */
    public $data;

    /**
     * UserRepositoryInterface constructor.
     *
     * @param Account $model
     */
    public function __construct(Account $model)
    {
        $this->model = $model;
    }

    public function create($userData)
    {
        $formFields = $userData->all();

        // Convert string to boolean
        $formFields['disabled'] = ($formFields['disabled'] === 'true');

        $user = User::create($formFields);

        // link the roles to the user
        if ($formFields['roles'] != null) {
            $user->roles()->sync($formFields['roles']);
        }

        dd($formFields->all());
    }

    public function update($userData, $user)
    {
        $formFields = $userData->all();

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
    }

    public function enable($user)
    {
        $user->disabled = false;
        $user->save();
    }

    public function disable($user)
    {
        $user->disabled = true;
        $user->save();
    }

    public function destroy($user)
    {
        $user->delete();
    }

    public function findById($id)
    {
        return User::find($id);
    }

    public function search($searchData = [], $sortFields = [])
    {
        // Search the users
        foreach ($searchData as $name => $value) {
            if ($value != null && $value > -1) {
                // null = empty and -1 for select inputs that are set to default
                $this->model = $this->model->where($name, 'like', "%{$value}%");
            }
        }

        // Sort the list
        $field = (empty($sortFields[0]) || is_null($sortFields[0])) ? 'id' : $sortFields[0];
        $sort = (empty($sortFields[1]) || is_null($sortFields[1])) ? 'asc' : $sortFields[1];

        $this->model = $this->model->orderBy($field, $sort);

        $this->filterOwn();

        return $this;
    }

    public function filterOwn()
    {
        // Show accounts we own.
        if (\Auth::user()->account->isSystemAccount()) {
            $this->data = $this->model->paginate(15);
        } else {
            $this->data = $this->model->where('account_id', '=', \Auth::user()->account->id)->paginate(15);
        }

        return $this;
    }
}
