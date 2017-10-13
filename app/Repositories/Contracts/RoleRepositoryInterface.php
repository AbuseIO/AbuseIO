<?php

namespace AbuseIO\Repositories\Contracts;

/**
 * Interface RoleRepositoryInterface.
 *
 * @method getSearchValues($namespace)
 * @method saveSearchValues($namespace)
 */
interface RoleRepositoryInterface
{
    public function create($roleData);

    public function update($roleData, $role);

    public function enable($role);

    public function destroy($role);

    public function disable($role);

    public function with($relations);

    public function search($searchFields = [], $sortFields = []);

    public function findById($id);
}
