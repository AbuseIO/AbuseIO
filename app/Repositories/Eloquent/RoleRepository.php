<?php

namespace AbuseIO\Repositories\Eloquent;

use AbuseIO\Models\Role;
use AbuseIO\Repositories\AbstractRepository;
use AbuseIO\Repositories\Contracts\RoleRepositoryInterface;

/**
 * Class RoleRepository.
 */
class RoleRepository extends AbstractRepository implements RoleRepositoryInterface
{
    /**
     * @var Role Model
     */
    protected $model;

    /**
     * @var RoleRepositoryInterface
     */
    public $data;

    /**
     * UserRepositoryInterface constructor.
     *
     * @param Role $model
     */
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function create($roleData)
    {
        // ..
    }

    public function update($roleData, $role)
    {
        // ..
    }

    public function enable($role)
    {
        // ..
    }

    public function disable($role)
    {
        // ..
    }

    public function destroy($role)
    {
        // ..
    }

    public function findById($id)
    {
        // ..
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
