<?php

namespace AbuseIO\Repositories\Contracts;

/**
 * Interface UserRepositoryInterface
 * @package AbuseIO\Repositories
 *
 * Methods inherited from AbstractRepository:
 * @method getSearchValues($namespace)
 * @method saveSearchValues($namespace)
 *
 */
interface UserRepositoryInterface
{
    public function create($userData);
    public function update($userData, $user);
    public function enable($user);
    public function destroy($user);
    public function disable($user);
    public function with($relations);
    public function search($searchFields = [], $sortFields = []);
    public function findById($id);
}
